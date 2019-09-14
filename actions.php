<?php

	/*
		This file creates every possible game action, a user can perform by sending a post-request.
		These are mainly activated by buttons with the html class 'option' and are sent through the code
		defined in 'ajax.js' and 'elements.js'
	*/

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	function registerAction($url, $func, $status = 'user') {
		global $app;
		global $container;

		if(is_callable($func)) {
			$action = $app->post($url, function (Request $request, Response $response, array $args) use ($func) {

				foreach($request->getParams() as $key => $value)
					$args[$key] = $value;

				$answer = $func($args, getAccount());
				return json_encode($answer);

			});

			if(!is_null($status))
				$action->add(new NeedsAuthentication($container['view'], $status, true));

		}

	};

	registerAction('/language/{lang}', function($args, $account) {

		$lang = $args['lang'];		
		return ['success' => setLang($lang)];

	});

	registerAction('/character/create', function($args, $account) {

		$clazz = $args['class'];
		$name = $args['name'];
		
		if($clazz && $name) {

			if(Character::where('name', $name)->first())
				return ['success' => false, 'message' => 'Name not available'];

			$clazz = Clazz::find($clazz);
			if(!$clazz && !$clazz->evolvesFrom->first())
				return ['success' => false, 'message' => 'Not a starter class'];

			$character = new Character;
			$character->name = $name;
			$character->race_id = 1;
			$character->class_id = $clazz->id;
			$character->health = 1000;
			$character->account_id = $account->id;

			$character->createPosition();
			$character->createParticipant();

			$character->save();
			return ['redirect' => '/profile'];

		}
		
		return ['success' => false];

	});

	registerAction('/character/select/{id}', function($args, $account) {

		$id = $args['id'];
		
		if($id)
			return ['success' => $account->select($id)];
		
		return ['success' => false];

	});

	registerAction('/character/evolve', function($args, $account) {
		
		$to = $args['to'] ?? null;
		
		if($to) {
			$character = $account->selected;
			if($character)
				return ['success' => $character->evolve($to)];
		}
		
		return ['success' => false];
		
	});

	registerAction('/character/learn/{skill}', function($args, $account) {
		
		$skill = Skill::find($args['skill'] ?? null);
		$character = $account->selected;
		
		if($skill) {
			if($character)
				return ['success' => $character->learn($skill)];
		}
		
		return ['success' => false, 'message' => 'Not a valid skill'];
		
	});

	registerAction('/character/travel', function($args, $account) {
		
		$id = $args['id'] ?? null;
		
		if($id) {
			$character = $account->selected;
			
			if($character)
				return ['success' => $character->travel($id)];
		}
		
		return ['success' => false];
		
	}); 

	registerAction('/dungeon/{option}', function($args, $account) {
		
		$character = $account->selected;

		if($character && !$character->battle) {
			
			$dungeon = $character->position->dungeon;
		   	if($dungeon) {
			
				$action = $args['option'];
				
				switch($action) {
					case 'search': return ['success' => $dungeon->search($character)];
					case 'leave': return ['success' => $dungeon->leave($character)];
					case 'down': return ['success' => $dungeon->down($character)];
					default: return ['success' => false, 'message' => "'$action' is not a valid action"];
				}
			}
			
			return ['success' => false, 'message' => 'You are not in a dungeon'];
		}

		return ['success' => false, 'message' => 'You are in a battle'];
		
	});

	registerAction('/battle/skill', function($args, $account) {
		
		$target = Participant::find($args['target'] ?? null);
		$skillID = $args['skill'] ?? null;
			
		if($target) {
			$character = $account->selected;

			if($character && ($battle = $character->participant->battle) && ($battle->active->id == $character->id)) {

				$skill = $character->skills()
					->where('id', $skillID)
					->wherePivot('nextUse', '<=', 0)
					->first();
				
				if($skillID && $skill) {
						
					if($skill->group) {
						if($target->character) $target = $battle->characters(true);
						else if($target->enemy) $target = $battle->enemies(true);
					} else $target = collect([$target]);
						
					if(!$skill->affectDead)
						$target = $target->where('health', '>', '0');

					if(!$skill->group) $target = $target->first();
					
					$battle->prepareTurn();
					$message = $skill->apply($target, $character);
					$battle->refresh();
					
					if($message) {
						$skill->timeout($character);
						$battle->next($message);
					}

					return ['success' => $message !== false, 'message' => $message];
				}

				return ['success' => false, 'message' => 'Choose a skill'];

			}
			return ['success' => false, 'message' => 'It\' not your turn'];

		}

		return ['success' => false, 'message' => 'Choose a target'];
		
	});

	registerAction('/battle/skip', function($args, $account) {
			
		$character = $account->selected;

		if($character && ($battle = $character->participant->battle) && ($battle->active_id == $character->id)) {
			
			$battle->prepareTurn();
			$message = $battle->next($character->name.' skipped');
			return ['success' => $message !== false, 'message' => $message];

		}
		
		return ['success' => false];
		
	});

	registerAction('/battle/run', function($args, $account) {
			
		$character = $account->selected;

		if($character && ($battle = $character->participant->battle) && ($battle->active_id == $character->id)) {
			
			$battle->prepareTurn();
			$message = $battle->run($character);
			return ['success' => $message !== false, 'message' => $message];

		}
		
		return ['success' => false];
		
	});

	registerAction('/inventory/take', function($args, $account) {
			
		$character = $account->selected;
		$stack = $args['stack'] ?? null;
		

		if($character && ($stack = Stack::find($stack)) && !$character->battle) {
			
			$message = $stack->take($character);
			return ['success' => $message !== false, 'message' => $message];

		}
		
		return ['success' => false];
		
	});

?>
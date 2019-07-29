<?php

	/*
		This file creates every possible game action, a user can perform by sending a post-request.
		These are mainly activated by buttons with the html class 'option' and are sent through the code
		defined in 'ajax.js' and 'elements.js'
	*/

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	function registerAction($url, $func, $status = null) {
		global $app;
		global $container;

		if(is_callable($func)) {
			$action = $app->post($url, function (Request $request, Response $response, array $args) use ($func) {

				foreach($request->getParams() as $key => $value)
					$args[$key] = $value;

				$answer = $func($args);
				return json_encode($answer);

			});

			if(!is_null($status)) {

				$action->add(new NeedsAuthentication($container['view'], $status, true));
			
			}

		}

	};

	registerAction('/character/create', function($args) {

		$account = getAccount();
		$clazz = $args['clazz'];
		$name = $args['name'];
		
		if($id && $account && $name) {

			if(Character::where('name', $name)->first())
				return ['success' => false, 'message' => 'Name not available'];

			$clazz = Clazz::find($clazz);
			if(!$clazz && !$clazz->relations['evolvesFrom']->first())
				return ['success' => false, 'message' => 'Not a starter class'];

			$character = new Character;
			$character->name = $name;
			$character->race = 1;
			$character->class = $clazz->id;
			$character->health = 1000;
			$character->account = $account->id;

			$character->save();
			return ['success' => true];

		}
		
		return ['success' => false];

	});

	registerAction('/character/select/{id}', function($args) {

		$id = $args['id'];
		$account = getAccount();
		
		if($id && $account)
			return ['success' => $account->select($id)];
		
		return ['success' => false];

	});

	registerAction('/character/evolve', function ($args) {
		
		$to = $args['to'] ?? null;
		$account = getAccount();
		
		if($to && $account) {
			$character = $account->relations['selected'];
			if($character)
				return ['success' => $character->evolve($to)];
		}
		
		return ['success' => false];
		
	});

	registerAction('/character/learn/{skill}', function ($args) {
		
		$skill = $args['skill'] ?? null;
		$account = getAccount();
		
		if($skill && $account) {
			$character = $account->relations['selected'];
			if($character)
				return ['success' => $character->learn($skill)];
		}
		
		return ['success' => false];
		
	});

	registerAction('/character/travel', function ($args) {
		
		$id = $args['id'] ?? null;
		$account = getAccount();
		
		if($id && $account) {
			$character = $account->relations['selected'];
			
			if($character)
				return ['success' => $character->travel($id)];
		}
		
		return ['success' => false];
		
	}); 

	registerAction('/dungeon/{option}', function ($args) {
		
		$account = getAccount();
		
		if($account) {
			$character = $account->relations['selected'];

			if($character && !$character->relations['battle']) {
				
				$dungeon = $character->relations['position']->relations['dungeon'];
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
		}

		return ['success' => false, 'message' => 'You are not logged in'];
		
	});

	registerAction('/battle/skill', function ($args) {
		
		$account = getAccount();
		$selected = $args['target'] ?? null;
		$skillID = $args['skill'] ?? null;
			
		if($account && $selected) {
			$character = $account->relations['selected'];

			if($character && ($battle = $character->relations['battle']) && ($battle->active == $character->id)) {

				$skill = $character->skills()
					->where('id', '=', $skillID)
					->wherePivot('nextUse', '<=', 0)
					->first();
				
				if($skillID && $skill) {
					
					switch($selected['type']) {
						case 'character': $target = $battle->relations['characters']; break;
						case 'enemy': $target = $battle->relations['enemies']; break;
					}
					
					if(isset($target) && $target) {
						
						if(!$skill->affectDead)
							$target = $target->where('health', '>', '0');
							
						if(!$skill->group)
							$target = $target->where('id', '=', $selected['id'])->first();
							
						$message = $skill->apply($target, $character);
						$battle->refresh();
						
						if($message) {
							$skill->timeout($character);
							$battle->next($message);
						}

						return ['success' => $message !== false, 'message' => $message];
					} else return ['success' => false, 'message' => 'Choose a valid target'];
				}

				return ['success' => false, 'message' => 'Choose a skill'];

			}
			return ['success' => false, 'message' => 'It\' not your turn'];

		}
		return ['success' => false, 'message' => 'Choose a target'];
		
	});

	registerAction('/battle/skip', function ($args) {
		
		$account = getAccount();
			
		if($account) {
			$character = $account->relations['selected'];

			if($character && ($battle = $character->relations['battle']) && ($battle->active == $character->id)) {
				
				$message = $battle->next($character->name.' skipped');
				return ['success' => $message !== false, 'message' => $message];

			}

		}
		
		return ['success' => false];
		
	});

	registerAction('/battle/run', function ($args) {
		
		$account = getAccount();
			
		if($account) {
			$character = $account->relations['selected'];

			if($character && ($battle = $character->relations['battle']) && ($battle->active == $character->id)) {
				
				$message = $battle->run($character);
				return ['success' => $message !== false, 'message' => $message];

			}

		}
		
		return ['success' => false];
		
	});

	registerAction('/inventory/take', function ($args) {
		
		$account = getAccount();
			
		if($account) {
			
			$character = $account->relations['selected'];
			$stack = $args['stack'] ?? null;
			

			if($character && ($stack = Stack::find($stack)) && !$character->relations['battle']) {
				
				$message = $stack->take($character);
				return ['success' => $message !== false, 'message' => $message];

			}

		}
		
		return ['success' => false];
		
	});

?>
<?php

	/*
		This file creates every possible game action, a user can perform by sending a post-request.
		These are mainly activated by buttons with the html class 'option' and are sent through the code
		defined in 'ajax.js' and 'elements.js'
	*/

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	function result($in, $adds = []) {

		if(!is_array($in)) $in = ['success' => $in];
		return array_merge($in, $adds);
	
	}

	function registerAction($url, $func, $status = 'user') {
		global $app;
		global $container;

		if(is_callable($func)) {
			$action = $app->post($url, function (Request $request, Response $response, array $args) use ($func) {

				foreach($request->getParams() as $key => $value)
					$args[$key] = $value;

				return json_encode(result($func($args, getAccount())));

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

		$clazz = $args['class'] ?? null;
		$name = $args['name'] ?? null;

		if($account->characters->isEmpty() || $account->hasPermission('create_chars')) {
		
			if($clazz && $name) {

				if(Character::where('name', $name)->first())
					return ['success' => false, 'message' => 'Name not available'];

				$clazz = Clazz::find($clazz);
				if(!$clazz && !$clazz->evolvesFrom->first())
					return ['success' => false, 'message' => 'Not a starter class'];
				
				$character = new Character;
				$character->name = $name;
				$character->race_id = 1;
				$character->account_id = $account->id;

				$character->save();
				$character->refresh();

				$character->createPosition();
				$character->createParticipant();

				$character->evolve($clazz);
				return ['redirect' => '/profile'];

			}

		}
		
		return false;

	});

	registerAction('/character/select/{id}', function($args, $account) {

		$character = Character::find($args['id'] ?? null);
		
		if($character)
			return ['success' => $account->select($character)];
		
		return false;

	});

	registerAction('/character/evolve', function($args, $account) {
		
		$to = Clazz::find($args['class'] ?? null);
		
		if($to) {
			$character = $account->selected;
			if($character)
				return ['success' => $character->evolve($to)];
		}
		
		return false;
		
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

	registerAction('/character/travel/{location}', function($args, $account) {
		
		$location = Location::find($args['location'] ?? null);
		
		if($location) {
			$character = $account->selected;
			
			if($character)
				return result($character->travel($location), ['reload' => 'map']);

		}
		
		return false;
		
	}); 

	registerAction('/character/enter/{dungeon}', function($args, $account) {
		
		$dungeon = Dungeon::find($args['dungeon'] ?? null);
		
		if($dungeon) {
			$character = $account->selected;
			
			if($character)
				return $character->enter($dungeon);
		}
		
		return false;
		
	}); 

	registerAction('/dungeon/{option}', function($args, $account) {
		
		$character = $account->selected;

		if($character && !$character->battle) {
			
			$dungeon = $character->position->dungeon;
		   	if($dungeon) {
			
				$action = $args['option'];
				
				switch($action) {
					case 'search': return ['success' => $dungeon->search($character)];
					case 'leave': return ['success' => $character->leave()];
					case 'down': return ['success' => $dungeon->down($character)];
					default: return ['success' => false, 'message' => "'$action' is not a valid action"];
				}
			}
			
			return ['success' => false, 'message' => 'You are not in a dungeon'];
		}

		return ['success' => false, 'message' => 'You are in a battle'];
		
	});

	registerAction('/battle/skill', function($args, $account) {
		
		$field = Field::find($args['target'] ?? null);
		$skillID = $args['skill'] ?? null;
		$character = $account->selected;
			
		if($field) {

			if($character && ($battle = $character->participant->battle) && ($battle->active->id == $character->id)) {

				if($character->participant->canTakeTurn()) {

					$skill = $character->participant->useableSkills()
								->where('id', $skillID)
								->first();
					
					if($skill)
						return $skill->use($field, $character->participant);

					return ['success' => false, 'message' => 'Choose a skill'];

				}

			}
			return ['success' => false, 'message' => 'It\' not your turn'];

		}

		return ['success' => false, 'message' => 'Choose a target'];
		
	});

	registerAction('/battle/skip', function($args, $account) {
			
		$character = $account->selected;

		if($character && ($battle = $character->participant->battle) && ($battle->active_id == $character->id)) {
			
			$battle->prepareTurn();
			$battle->addMessage(new Translation('skipped', [$character->name()]));
			$success = $battle->next();
			return ['success' => $success];

		}
		
		return false;
		
	});

	registerAction('/battle/move', function($args, $account) {
			
		$character = $account->selected;
		$field = Field::find($args['target'] ?? null);

		if($character && ($battle = $character->participant->battle) && ($battle->active_id == $character->id)) {

			if($character->participant->canTakeTurn() && $field) {

				if($field && $field->canMoveOn()) {
					$battle->prepareTurn();

					$character->participant->field->participant_id = null;
					$character->participant->field->save();
					$field->participant_id = $character->participant->id;
					$field->save();

					$battle->refresh();
					$battle->addMessage(new Translation('moved', [$character->name()]));
					$battle->next();

					return true;

				}

				return ['success' => false, 'message' => 'Select an empty field'];

			}

		}
		
		return false;
		
	});

	registerAction('/battle/run', function($args, $account) {
			
		$character = $account->selected;

		if($character && ($battle = $character->participant->battle) && ($battle->active_id == $character->id)) {

			if($character->participant->canTakeTurn()) {
			
				$battle->prepareTurn();
				$message = $battle->run($character);
				return ['success' => $message !== false, 'message' => $message];

			}

		}
		
		return false;
		
	});

	registerAction('/inventory/take', function($args, $account) {
			
		$character = $account->selected;
		$stack = Stack::find($args['stack'] ?? null);
		$slot = Slot::find($args['slot'] ?? null);	

		if($character && $stack && $slot && !$character->battle) {

			$fits = $slot->fits($stack, $character);

			if($fits['success']) {
			
				$stack->slot_id = $slot->id;
				$stack->save();
				$character->refresh();
				Stack::tidy($character);
				
			}
			
			return result($fits, ['reload' => 'inventory']);

		}
		
		return false;
		
	});

	registerAction('/inventory/action', function($args, $account) {
			
		$character = $account->selected;
		$stack = Stack::find($args['stack'] ?? null);
		$action = $args['action'] ?? null;

		if($character && $stack && $action) {

			if($character->participant->canTakeTurn()) {

				$target = Field::find($args['target'] ?? null) ?? $character;
				if($target) $target = $target->participant;

				if($target && $stack->item->can($action)) {

					$battle = $character->participant->battle;
					if($battle) $battle->prepareTurn();

					$success = $stack->item->$action($stack, $target);

					if($battle && $success) $battle->next();
					$character->refresh();
					Stack::tidy($character);

					return $success;

				}
			}			
			
			return ['success' => false];

		}
		
		return false;
		
	});

?>
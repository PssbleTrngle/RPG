<?php

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
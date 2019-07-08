<?php 

	$app->registerAction('/character/select/{id}', function($args) {

		$id = $args['id'];
		$account = getAccount();
		
		if($id && $account)
			return ['success' => $account->select($id)];
		
		return ['success' => false];

	});

	/*

	$app->registerAction('/character/evolve', function ($args) {
		
		$to = $args['to'] ?? null;
		$account = getAccount();
		
		if($to && $account) {
			$character = $account->relations['selected'];
			if($character)
				return ['success' => $character->evolve($to)];
		}
		
		return ['success' => false];
		
	});

	$app->registerAction('/character/travel', function ($args) {
		
		$id = $args['id'] ?? null;
		$account = getAccount();
		
		if($id && $account) {
			$character = $account->relations['selected'];
			
			if($character)
				return ['success' => $character->travel($id)];
		}
		
		return ['success' => false];
		
	}); 

	$app->registerAction('/dungeon/{action}', function ($args) {
		
		$account = getAccount();
		
		if($account) {
			$character = $account->relations['selected'];

			if($character && !$character->relations['battle']
			   && $dungeon = $character->relations['position']->relations['dungeon']) {
				
				switch($args['action']) {
					case 'search': return ['success' => $dungeon->search($character)];
					case 'leave': return ['success' => $dungeon->leave($character)];
				}
			}
		}

		return ['success' => false];
		
	});

	$app->registerAction('/battle/skill', function ($args) {
		
		$account = getAccount();
		$selected = $args['target'] ?? null;
		$skillID = $request->getParams()['skill'] ?? null;
			
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

						return json_encode(['success' => $message !== false, 'message' => $message]);
					} else return json_encode(['success' => false, 'message' => 'Choose a valid target']);
				}

				return json_encode(['success' => false, 'message' => 'Choose a skill']);

			}
			return json_encode(['success' => false, 'message' => 'It\' not your turn']);

		}
		return json_encode(['success' => false, 'message' => 'Choose a target']);
		
	});

	$app->registerAction('/battle/skip', function ($args) {
		
		$account = getAccount();
			
		if($account) {
			$character = $account->relations['selected'];

			if($character && ($battle = $character->relations['battle']) && ($battle->active == $character->id)) {
				
				$message = $battle->next($character->name.' skipped');
				return json_encode(['success' => $message !== false, 'message' => $message]);

			}

		}
		
		return json_encode(['success' => false]);
		
	});

	$app->registerAction('/battle/run', function ($args) {
		
		$account = getAccount();
			
		if($account) {
			$character = $account->relations['selected'];

			if($character && ($battle = $character->relations['battle']) && ($battle->active == $character->id)) {
				
				$message = $battle->run($character);
				return json_encode(['success' => $message !== false, 'message' => $message]);

			}

		}
		
		return json_encode(['success' => false]);
		
	});

	$app->registerAction('/inventory/take', function ($args) {
		
		$account = getAccount();
			
		if($account) {
			
			$character = $account->relations['selected'];
			$stack = $request->getParams()['stack'] ?? null;
			

			if($character && ($stack = Stack::find($stack)) && !$character->relations['battle']) {
				
				$message = $stack->take($character);
				return json_encode(['success' => $message !== false, 'message' => $message]);

			}

		}
		
		return json_encode(['success' => false]);
		
	});

	*/

?>
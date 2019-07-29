<?php

	function gitPull() {

		return exec('git pull');

	}

	registerAction('/admin/update', function($args) {

		$msg = gitPull();
		return ['success' => $msg !== false, 'message' => $msg];

	}, 'admin');
	
	$app->get('/admin/validate', function (Request $request, Response $response, array $args) {
		
		$level = $request->getParams()['level'] ?? 0b11111111;
		$this->view->render($response, 'admin/validate.twig', ['log' => validate($level)]);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

	$app->get('/admin/loot', function (Request $request, Response $response, array $args) {
		
		$log = "";
		foreach(NPC::all() as $npc) {
			$log .= '<p>Loot for '.$npc->name.'</p>';
			
			foreach($npc->relations['loot'] as $item)
				$log .= '<li>'.$item->name.'</li>';
		}
			
		$this->view->render($response, 'admin/validate.twig', ['log' => $log]);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

	$app->get('/admin/post', function (Request $request, Response $response, array $args) {
		
		$this->view->render($response, 'admin/post.twig', []);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

	$app->post('/admin/post', function (Request $request, Response $response) {
		
		$args = $request->getParams()['args'] ?? null;
		$action = $request->getParams()['action'] ?? null;
		
		if($action)
			return $response->withRedirect('/'.$action, 307);
		$this->view->render($response, 'post.twig', []);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

	$app->get('/admin/classes', function (Request $request, Response $response, array $args) {
		
		return $this->view->render($response, 'admin/classes.twig', ['classes' => Clazz::all()]);
		
	})->add(new NeedsAuthentication($container['view'], 'betatest'));

	$app->get('/admin/level', function (Request $request, Response $response, array $args) {
		
		$points = [];		
		$xp = 0;
		
		for($level = 0; $level < 60 && $xp < 10000; $level++) {
			$point = [];
			$point['required'] = Character::requiredXp($level);
			$xp += $point['required'];
			$point['total'] = $xp;
			$point['test'] = Character::levelFrom($xp);
			$points[] = $point;
		}
		
		return $this->view->render($response, 'admin/level.twig', ['points' => $points]);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

?>
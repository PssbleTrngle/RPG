<?php

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	function gitPull() {

		return shell_exec('git pull');

	}

	registerAction('/admin/update', function($args) {

		$msg = gitPull();
		$success = strpos($msg, 'changed') !== false;
		return ['success' => $success, 'message' => $msg];

	}, 'admin');
	
	$app->get('/admin/test', function (Request $request, Response $response, array $args) {
		
		$log = json_encode(getAccount()->selected->participant->effects->where('id', 1)->first()->pivot->countdown);
		$this->view->render($response, 'admin/validate.twig', ['log' => $log]);
		
	});
	
	$app->get('/admin/validate', function (Request $request, Response $response, array $args) {
		
		$level = $request->getParams()['level'] ?? 0b11111111;
		$this->view->render($response, 'admin/validate.twig', ['log' => validate($level)]);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

	$app->get('/admin/loot', function (Request $request, Response $response, array $args) {
		
		$log = "";
		foreach(NPC::all() as $npc) {
			$log .= '<p>Loot for '.$npc->name.'</p>';
			
			foreach($npc->loot as $item)
				$log .= '<li>'.$item->name.'</li>';
		}
			
		$this->view->render($response, 'admin/validate.twig', ['log' => $log]);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

	$app->get('/admin/post', function (Request $request, Response $response, array $args) {
		
		$this->view->render($response, 'admin/post.twig', []);
		
	})->add(new NeedsAuthentication($container['view'], 'admin'));

	$app->get('/admin/classes', function (Request $request, Response $response, array $args) {		
		return $this->view->render($response, 'admin/classes.twig', ['classes' => Clazz::all()]);		
	})->add(new NeedsAuthentication($container['view'], 'betatester'));

	$app->get('/admin/list/{class}', function (Request $request, Response $response, array $args) {

		$class = $args['class'];
		$objects = [];

		if(is_subclass_of($class, 'BaseModel')) $objects = $class::all();

		return $this->view->render($response, 'admin/list.twig', ['objects' => $objects, 'search' => $class]);

	})->add(new NeedsAuthentication($container['view'], 'betatester'));

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
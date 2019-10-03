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

	}, 'editor');
	
	$app->get('/admin/test', function (Request $request, Response $response, array $args) {
		global $capsule;

		echo json_encode(getAccount()->selected->participant->battle);
		
	})->add(new NeedsAuthentication($container['view'], 'tester'));
	
	$app->get('/admin/validate', function (Request $request, Response $response, array $args) {
		
		$level = $request->getParams()['level'] ?? 0b11111111;
		$this->view->render($response, 'admin/validate.twig', ['log' => validate($level)]);
		
	})->add(new NeedsAuthentication($container['view'], 'tester'));

	$app->get('/admin/post', function (Request $request, Response $response, array $args) {		
		$this->view->render($response, 'admin/post.twig', []);		
	})->add(new NeedsAuthentication($container['view'], 'tester'));

	$app->get('/view/classes', function (Request $request, Response $response, array $args) {		
		return $this->view->render($response, 'admin/classes.twig', ['classes' => Clazz::all()]);		
	})->add(new NeedsAuthentication($container['view'], 'tester'));

	$app->get('/test/battle', function (Request $request, Response $response, array $args) {

		$battle = new Battlefield;

		return $this->view->render($response, 'battle.twig', ['battle' => $battle]);		
	
	})->add(new NeedsAuthentication($container['view'], 'tester'));
	
?>
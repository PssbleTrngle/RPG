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

		echo '<pre>';
		var_dump(Clazz::find(1)->skills());
		echo '</pre>';
		
	})->add(new NeedsAuthentication($container['view'], 'tester'));
	
	$app->get('/admin/validate', function (Request $request, Response $response, array $args) {
		
		$level = $request->getParams()['level'] ?? 0b11111111;
		$this->view->render($response, 'admin/validate.twig', ['log' => validate($level)]);
		
	})->add(new NeedsAuthentication($container['view'], 'tester'));

	$app->get('/admin/post', function (Request $request, Response $response, array $args) {		
		$this->view->render($response, 'admin/post.twig', []);		
	})->add(new NeedsAuthentication($container['view'], 'tester'));

	$app->get('/admin/classes', function (Request $request, Response $response, array $args) {		
		return $this->view->render($response, 'admin/classes.twig', ['classes' => Clazz::all()]);		
	})->add(new NeedsAuthentication($container['view'], 'tester'));

	$app->get('/admin/list/{class}', function (Request $request, Response $response, array $args) {

		$class = $args['class'];
		$objects = [];

		if(is_subclass_of($class, 'BaseModel')) $objects = $class::all();

		return $this->view->render($response, 'admin/list.twig', ['objects' => $objects, 'search' => $class]);

	})->add(new NeedsAuthentication($container['view'], 'tester'));

?>
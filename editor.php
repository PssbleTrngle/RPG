<?php

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	registerAction('/editor/update', function($args) {

		

	}, 'editor');
	
	$app->get('/edit/{class}', function (Request $request, Response $response, array $args) {
		
		$class = $args['class'];
		$objects = is_subclass_of($class, 'BaseModel') ? $class::all() : [];
		$this->view->render($response, 'editor/list.twig', ['objects' => $objects, 'class' => $class]);
		
	})->add(new NeedsAuthentication($container['view'], 'editor'));
	
	$app->get('/edit/{class}/{id}', function (Request $request, Response $response, array $args) {
		
		$class = $args['class'];
		$id = $args['id'];

		$object = is_subclass_of($class, 'BaseModel') ? $class::find($id) : null;
		$this->view->render($response, 'editor/edit.twig', ['object' => $object, 'class' => $class]);
		
	})->add(new NeedsAuthentication($container['view'], 'editor'));

?>
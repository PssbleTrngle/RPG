<?php

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	registerAction('/editor/update', function($args) {
		global $capsule;

		$key = $args['key'] ?? null;		
		$table = $args['table'] ?? null;		
		$value = $args['value'] ?? null;		
		$pivots = $args['pivots'] ?? [];

		if($table && $value && $key) {

			$object = [ $key => $value ];
			foreach($pivots as $pivot => $val) {
				$split = explode('.', $pivot);
				$pivot = $split[sizeof($split) - 1];
				$object[$pivot] = $val;
			}

			return $capsule->table($table)->insert($object);

		}	

		return false;	

	}, 'editor');
	
	$app->get('/edit/{class}', function (Request $request, Response $response, array $args) {
		
		$class = $args['class'];
		$objects = is_subclass_of($class, 'BaseModel') ? $class::all() : [];
		$this->view->render($response, 'editor/list.twig', ['objects' => $objects, 'class' => $class]);
		
	})->add(new NeedsAuthentication($container['view'], 'tester'));
	
	$app->get('/edit/{class}/{id}', function (Request $request, Response $response, array $args) {
		
		$class = $args['class'];
		$id = $args['id'];
		$manys = manysFor($class);

		$object = is_subclass_of($class, 'BaseModel') ? $class::find($id) : null;
		$this->view->render($response, 'editor/edit.twig', ['object' => $object, 'manys' => $manys, 'class' => $class]);
		
	})->add(new NeedsAuthentication($container['view'], 'tester'));

	function manysFor($class) {

		switch(strtolower($class)) {

			case 'clazz': return [
				['key' => 'class_id', 'relation' => 'skills', 'pivots' => ['skill_id' => Skill::all(),'pivot.level' => 'number']],
				['key' => 'to', 'relation' => 'evolvesFrom', 'pivots' => ['from' => Clazz::all(), 'evolution.level' => 'number']],
				['key' => 'from', 'relation' => 'evolvesTo', 'pivots' => ['to' => Clazz::all(), 'evolution.level' => 'number']]];

		}

		return [];

	}

?>
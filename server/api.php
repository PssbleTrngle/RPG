<?php

	/**  Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$app->get('/api/account', function (Request $request, Response $response, array $args) {
		return $response
			->withHeader('Content-type', 'application/json')
			->withJson(getAccount());
	});

?>
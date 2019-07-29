<?php

	/*
		This file handles all post request regarding login/logout/account creation
	*/

	$app->get('/logout', function (Request $request, Response $response, array $args) {		
		unset($_SESSION['account']);
		return $response->withRedirect($request->getParams()['next'] ?? '/');		
	});

	$app->post('/login', function (Request $request, Response $response, array $args) {

		$username = $request->getParams()['username'];
		$password = $request->getParams()['password'];
		
		$account = Account::where('username', '=', $username)->first();
		
		if ($account != null && password_verify($password, $account->password_hash)) {
			$_SESSION['account'] = $account->id;
			return $response->withRedirect($request->getParams()['next'] ?? '/');
		} else {
			return $this->view->render($response, 'login.twig', ['failed' => true]);
		}
		
	});

	$app->post('/signup', function (Request $request, Response $response, array $args) {

		$username = $request->getParams()['username'] ?? null;
		$password = $request->getParams()['password'] ?? null;
		
		if($username && $password) {

			$account = Account::where('username', '=', $username)->first();
			if($account)
				return $this->view->render($response, 'login.twig', ['exists' => true]);

			$account = new Account;
			$account->username = $username;
			$account->password_hash = password_hash($password, PASSWORD_DEFAULT);
			$account->status = Status::where('name', 'user')->first()->id;
			
			$account->save();
			$account->refresh();

			$_SESSION['account'] = $account->id;
			return $response->withRedirect('/');
			
		}
		
		return $this->view->render($response, 'login.twig', ['failed' => true]);
		
	});

?>
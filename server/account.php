<?php

	/*
		This file handles all post request regarding login/logout/account creation
	*/

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$ACCOUNT = null;

	function getAccount() {
		global $ACCOUNT;
		global $static_account;

		if(!is_null($ACCOUNT)) return $ACCOUNT;

	    if($static_account) {
	    	$account = Account::find($static_account);
	    	if($account) return $account;
	    }

	    if (isset($_SESSION['account'])) {

	    	$account = Account::where('id', $_SESSION['account'])->first();

			if($account->selected) {
				Stack::tidy($account->selected);
				$account->validate();	
			}

			$ACCOUNT = $account;
			return $ACCOUNT;

	    }

	    return null;
	}

	$app->get('/stats/users', function (Request $request, Response $response, array $args) {	

		$json = [
			"schemaVersion" => 1,
			"label" => "Users",
			"message" => "".Account::all()->count(),
			"color" => "yellow"
		];

		return json_encode($json);

	});

	$app->get('/stats/characters', function (Request $request, Response $response, array $args) {	

		$json = [
			"schemaVersion" => 1,
			"label" => "Users",
			"message" => "".Character::all()->count(),
			"color" => "yellow"
		];

		return json_encode($json);

	});

	$app->get('/logout', function (Request $request, Response $response, array $args) {		
		unset($_SESSION['account']);
		return $response->withRedirect($request->getParams()['next'] ?? '/');
	});

	$app->post('/login', function (Request $request, Response $response, array $args) {

		$username = $request->getParams()['username'];
		$password = $request->getParams()['password'];
		
		$account = Account::where('username', $username)->first();
		
		if ($account && password_verify($password, $account->password_hash)) {
			$_SESSION['account'] = $account->id;
			return $response->withRedirect($request->getParams()['next'] ?? '/');
		} else {
			return $this->view->render($response, 'login.twig', ['failed' => true]);
		}
		
	});

	$app->post('/signup', function (Request $request, Response $response, array $args) {
		global $capsule;

		$username = $request->getParams()['username'] ?? null;
		$password = $request->getParams()['password'] ?? null;
		
		if($username && $password) {

			$account = Account::where('username', $username)->first();
			if($account) {
				#$account->addPermission(Permission::where('name', 'user')->first());
				return $this->view->render($response, 'login.twig', ['exists' => true]);
			}

			$account = new Account;
			$account->username = $username;
			$account->password_hash = password_hash($password, PASSWORD_DEFAULT);
			
			$account->save();
			$account->refresh();

			$account->addPermission(Permission::where('name', 'user')->first());

			$_SESSION['account'] = $account->id;
			return $response->withRedirect('/');
			
		}
		
		return $this->view->render($response, 'login.twig', ['failed' => true]);
		
	});

?>
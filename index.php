<?php

	require 'vendor/autoload.php';

	include_once "config.php";
	include_once "database/database.php";
	include_once "database/validation.php";

	session_start();

	/* Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$configuration = [
		'settings' => [ 'displayErrorDetails' => true ],
	];
	$container = new \Slim\Container($configuration);
	$app = new \Slim\App($container);

	/* Twig Template engine */
	$loader = new \Twig\Loader\FilesystemLoader("templates");
	$twig = new \Twig\Environment($loader);

	$container['view'] = function ($container) {

    	$view = new \Slim\Views\Twig('templates');
	    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
	    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('uri', function () {
	        return $_SERVER['REQUEST_URI'];
	    }));

	    /*
		    Used in templates to access a certain icon (for example of a class or an item)
		    or return the 'missing.png' image 
	    */
	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('icon', function ($path) {
			
			$path = strtolower(str_replace(' ', '_', $path));
			$img = 'missing.png';
			
			if(endsWith($path, '/random')) {
				
				$dir = str_replace('/random', '', $path);
				
				$all = scandir('assets/img/'.$dir);
				$all = array_filter($all, function($val) use($all) {
					return endsWith($val, '.png') || endsWith($val, '.svg');
				});			
				
				if(!empty($all)) $img = $dir.'/'.$all[array_rand($all)];
				
			}
			else if(file_exists("assets/img/$path.svg")) $img = $path.'.svg';
			else if(file_exists("assets/img/$path.png")) $img = $path.'.png';
							
	        $html = "<img class='icon' src='/assets/img/$img'></img>";			
			return $html;
	    }));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('account', function () {
	        return getAccount();
	    }));

	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('age', function ($time) {
	        $time = strtotime($time);
			return round(abs($time - time()) / 24 / 60 / 60);
	    }));

	    return $view;
	};

	/* The Home page, acting as the game screen */
	$app->get('/', function(Request $request, Response $response, array $args) {

		$account = getAccount();
		if($account) {
		
			if(($selected = $account->relations['selected']) && ($battle = $selected->relations['battle'])) {
				
				$won = true;
				foreach($battle->relations['enemies'] as $enemy)
					$won &= $enemy->health <= 0;
				
				if($won) $battle->win();
			
			}
			
			return $this->view->render($response, 'home.twig', []);
		}
		
	})->add(new NeedsAuthentication($container['view'], 'user'));

	/* A functioning, but no yet used map screen */
	$app->get('/map', function (Request $request, Response $response, array $args) {
		
		$expanded = $request->getParams()['area'] ?? null;
		$this->view->render($response, 'map.twig', ['areas' => Area::all(), 'expanded' => $expanded]);
		
	})->add(new NeedsAuthentication($container['view'], 'user'));

	$app->get('/login', function (Request $request, Response $response, array $args) {
		$this->view->render($response, 'login.twig', []);
	});

	$app->get('/profile', function (Request $request, Response $response, array $args) {
		$this->view->render($response, 'profile.twig', []);
	})->add(new NeedsAuthentication($container['view'], 'user'));

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

	/* A variety of admin pages, used mainly for debugging */

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

	/*
		Used by 'actions.php' to register to user input actions.
		These are handled via post-requests
	*/

	function registerAction($url, $func) {
		global $app;

		if(is_callable($func))
			$app->post($url, function (Request $request, Response $response, array $args) use ($func) {

			foreach($request->getParams() as $key => $value) {
				$args[$key] = $value;
			}

			$answer = $func($args);
			return json_encode($answer);

		});

	};

	include_once 'actions.php';

	function getAccount() {
	    if (isset($_SESSION['account'])) {
	    	$account = Account::where('id', $_SESSION['account'])->first();
			Stack::tidy($account->relations['selected']);
			return $account;
	    }
	    return null;
	}

	$container['notFoundHandler'] = function ($container) {
	    return function (Request $request, Response $response) use ($container) {
	        return $container->view->render($response->withStatus(404), 'handlers/404.twig');
	    };
	};

    $container['errorHandler'] = function ($container) {
        return function (Request $request, Response $response, $exception) use ($container) {
            return $container['view']->render($container['response']->withStatus(500), 'handlers/500.twig', ['error' => $exception]);
        };
    };
    $container['phpErrorHandler'] = function ($container) {
        return function (Request $request, Response $response, $exception) use ($container) {
            return $container['view']->render($container['response']->withStatus(500), 'handlers/500.twig', ['error' => $exception]);
        };
    };

	function endsWith($haystack, $needle) {
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

	/* Used by pages requiring a certain status like admin or betatester */
	class NeedsAuthentication {
	    private $view;
	    private $accessLevel;

	    public function __construct(\Slim\Views\Twig $view, $accessLevel) {
	        $this->view = $view;
	        $status = Status::where('name', $accessLevel)->first() ?? Status::find(100);
	        $this->accessLevel = $status->id;
	    }
	    public function __invoke($request, $response, $next) {
			
			$account = getAccount();
			
	        if ($account == null) {
	            return $response->withRedirect('/login?next=' . $request->getUri()->getPath());
	        }
	        if ($account->status < $this->accessLevel) {
	            return $this->view->render($response->withStatus(403), 'handlers/403.twig');
	        }
	        return $next($request, $response);
	    }
	}

	$app->run();
?>
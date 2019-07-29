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


	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('hasStatus', function ($account, $status) {
			$status = Status::where('name', $status)->first();
	        return $account !== false && $status && $account->status >= $status->id;
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
	        $age = round(abs($time - time()) / 24 / 60 / 60);
			return $age.' Day'.($age > 1 ? 's' : '');
	    }));

	    return $view;
	};

	/* The Home page, acting as the game screen */
	$app->get('/', function(Request $request, Response $response, array $args) {

		$account = getAccount();
		if($account) {
		
			$selected = $account->relations['selected'];
			if($selected) {

				if($battle = $selected->relations['battle']) {
					
					$won = true;
					foreach($battle->relations['enemies'] as $enemy)
						$won &= $enemy->health <= 0;
					
					if($won) $battle->win();
				
				}
				
				return $this->view->render($response, 'home.twig', []);

			}

			return $response->withRedirect('/profile');

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

	$app->get('/profile/create', function (Request $request, Response $response, array $args) {

		$starters = Clazz::all()->filter(function($clazz, $i) {
			return $clazz->relations['evolvesFrom']->count() > 0;
		});

		$this->view->render($response, 'create.twig', [ 'starters' => $starters ]);
	
	})->add(new NeedsAuthentication($container['view'], 'user'));

	/*
		Used by 'actions.php' to register to user input actions.
		These are handled via post-requests
	*/

	include_once 'actions.php';
	include_once 'admin.php';
	include_once 'account.php';

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
	    private $post;

	    public function __construct(\Slim\Views\Twig $view, $accessLevel, $post = false) {
	        $this->view = $view;
	        $status = Status::where('name', $accessLevel)->first() ?? Status::find(100);
	        $this->accessLevel = $status->id;
	        $this->post = $post;
	    }
	    public function __invoke($request, $response, $next) {
			
			$account = getAccount();
			
	        if ($account == null) {
	        	if($this->post) return json_encode(['success' => false, 'message' => 'You are not logged in']);
	            return $response->withRedirect('/login?next=' . $request->getUri()->getPath());
	        }
	        if ($account->status < $this->accessLevel) {
	        	if($this->post) return json_encode(['success' => false, 'message' => 'You are not allowed to do this']);
	            return $this->view->render($response->withStatus(403), 'handlers/403.twig');
	        }
	        return $next($request, $response);
	    }
	}

	$app->run();
?>
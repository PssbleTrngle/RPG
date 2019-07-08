<?php

	require 'vendor/autoload.php';

	include_once "config.php";
	include_once "database/database.php";
	include_once "database/validation.php";

	session_start();

	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$configuration = [
		'settings' => [ 'displayErrorDetails' => true ],
	];
	$container = new \Slim\Container($configuration);
	$app = new \Slim\App($container);

	$loader = new \Twig\Loader\FilesystemLoader("templates");
	$twig = new \Twig\Environment($loader);

	$container['view'] = function ($container) {

    	$view = new \Slim\Views\Twig('templates');
	    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
	    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('uri', function () {
	        return $_SERVER['REQUEST_URI'];
	    }));
		
		$view->getEnvironment()->addFilter(new Twig_SimpleFilter('as_array', function ($stdClassObject) {
			$array = array();
			foreach ($stdClassObject as $key => $value) {
				$array[$key] = array($key, $value);
			}
			return $array;
		}));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('icon', function ($path, $colors = []) {
			
			$path = strtolower(str_replace(' ', '_', $path));
			$images = [];
			if(is_dir("assets/img/$path")) {
			
				foreach(scandir("assets/img/$path") as $key => $file) if(!is_dir($file)) {
					
					$regex = '/(.*?)_([0-9]*?)\.png/';
					preg_match($regex, $file, $matches);
					$index = $matches[2] ?? null;
					$images["$path/$file"] = $colors[$index] ?? false;
				}
				
			} #else if(file_exists("assets/img/$path.svg")) $images[$path.'.svg'] = false;
			else if(file_exists("assets/img/$path.png")) $images[$path.'.png'] = false;
			
			if(empty($images)) $images['missing.png'] = false;
			
			$html = '<div>';
			foreach($images as $img => $color) {
				
				$img = "/assets/img/$img";
				if($color) $style = "mask-image: url($img); -webkit-mask-image: url($img); mask-size: 100%; -webkit-mask-size: 100%; display: block; background-color: $color; mask-repeat: no-repeat; -webkit-mask-repeat: no-repeat;";
				else $style = '';
				
	        	$html .= 	"<div class='icon'>
								<img class='icon' src='$img'></img>
								<div style='$style' class='colored'></div>
							</div>";	
			}
			
			return $html.'</div>';
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
		
		return $response->withRedirect('/login');
		
	});

	$app->get('/map', function (Request $request, Response $response, array $args) {
		
		$expanded = $request->getParams()['area'] ?? null;
		$this->view->render($response, 'map.twig', ['areas' => Area::all(), 'expanded' => $expanded]);
		
	});

	$app->get('/login', function (Request $request, Response $response, array $args) {
		$this->view->render($response, 'login.twig', []);
	});

	$app->get('/profile', function (Request $request, Response $response, array $args) {
		$this->view->render($response, 'profile.twig', []);
	});

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
		
		$log .= "<br>";
		
		foreach(Enemy::all() as $enemy) {
			$log .= '<p>Test Loot for '.$enemy->name().'</p>';
			
			$enemy->getLoot();
			
			/*
			foreach($enemy->getLoot()['items'] as $item)
				$log .= '<li>'.$item->name.'</li>';
			*/
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
		
		/*
		foreach(['Wizard', 'Touched', 'Spirit', 'Gate', 'Narrator', 'Fate', 'Death', 'Wind', 'Seal', 'Truth', 'Pain'] as $i => $clazz) {
			$c = new Clazz;
			$c->id = 400 + $i;
			$c->name = $clazz;
			$c->save();
		}
		*/
		
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

	function registerAction($url, $func) {
		global $app;

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
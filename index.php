<?php

	require 'vendor/autoload.php';

	include_once "config.php";
	include_once "database/database.php";
	include_once "database/validation.php";
	include_once "localisation.php";
	include_once "helper.php";

	session_start();

	/**  Used for Routing */
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$configuration = ['settings' => [ 'displayErrorDetails' => true ]];
	$container = new \Slim\Container($configuration);
	$app = new \Slim\App($container);

	/* Twig Template engine */
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader, [ 'debug' => true]);
	$twig->addExtension(new \Twig\Extension\DebugExtension());

	$container['view'] = function ($container) {

    	$view = new \Slim\Views\Twig('templates');
	    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
	    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('langs', function () {
	        return ['en', 'de', 'fr', 'it', 'cyber'];
	    }));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('uri', function () {
	        return $_SERVER['REQUEST_URI'];
	    }));

		$view->getEnvironment()->addFilter(new Twig_SimpleFilter('roman', function ($number) {
			if(is_numeric($number)) {
				if(getLang() == 'cyber') return '0x'.str_pad(decbin($number), 4, '0', STR_PAD_LEFT);
				return toRoman($number);
			}
			return '?';
	    }));


		$view->getEnvironment()->addFilter(new Twig_SimpleFilter('removeCamel', function ($camel) {
			$camel = preg_replace('/([A-Z])/', ' $1', $camel);
			$camel = preg_replace('/_([a-zA-Z])/', ' $1', $camel);
			return ucfirst($camel);
	    }));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('format', function ($key, $vars = []) {
	    	if(is_string($vars)) $vars = [ $vars ];
			return format($key, $vars);
	    }));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('styles', function () {
	    	$lang = getLang();
	    	$general = glob("assets/css/*.css");
	    	$lang = $lang ? glob("assets/css/$lang/*.css") : [];
			return array_merge($general, $lang);
	    }));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('scripts', function () {
	    	$lang = getLang();
	    	$general = glob("assets/scripts/*.js");
	    	$lang = $lang ? glob("assets/scripts/$lang/*.js") : [];
			return array_merge($general, $lang);
	    }));

	    /*
		    Used in templates to access a certain icon (for example of a class or an item)
		    or return the 'missing.png' image 
	    */
	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('icon', function ($path, $color = null, $srcOnly = false) {
			return createIcon($path, $color, $srcOnly);
	    }));

	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('icon', function ($object, $srcOnly = false) {

	    	if(method_exists($object, 'icon')) {
	    		if(method_exists($object, 'color')) {
	    			$color = $object->color();
	    			if($color) return createIcon($object->icon(), $color, $srcOnly);
	    		} 
	    		return createIcon($object->icon(), null, $srcOnly);
	    	}
			
			return createIcon(null, null, $srcOnly);

	    }));

	    $view->getEnvironment()->addFunction(new Twig_SimpleFunction('account', function () {
	        return getAccount();
	    }));

	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('all', function ($class) {
	        if(is_subclass_of($class, 'BaseModel')) return $class::all();
	        return collect([]);
	    }));

	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('call', function ($class, $method) {
	        return $class->$method();
	    }));

	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('class', function ($object) {
	        return (new \ReflectionClass($object))->getShortName();
	    }));

	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('byName', function ($class, $name) {
	        if(is_subclass_of($class, 'BaseModel')) return $class::where('name', $name)->first();
	        return null;
	    }));

	    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('age', function ($time) {
	        $time = strtotime($time);
	        $age = round(abs($time - time()) / 24 / 60 / 60);
	        return format('time.'.($age > 1 ? 'days' : 'day'), [ $age ]);
	    }));

	    return $view;
	};

	/* The Home page, acting as the game screen */
	$app->get('/', function(Request $request, Response $response, array $args) {

		$selected = getAccount()->selected;

		if($selected) {
			if($selected->participant->battle) 
				return $this->view->render($response, 'battle.twig', ['battle' => $selected->participant->battle]);
			return $this->view->render($response, 'home.twig', []);
		}

		return $response->withRedirect('/profile');
		
	})->add(new NeedsAuthentication($container['view'], 'user'));

	/* Can be used by other websites to request icons */
	$app->get('/icon/{icon:[a-zA-Z/]*}', function(Request $request, Response $response, array $args) {

		$icon = $args['icon'] ?? null;

		$url = createIcon($icon, null, true);
		$icon = file_get_contents($url);

		if(pathinfo($url, PATHINFO_EXTENSION) == 'svg') {
			$response->write($icon);
		}

		else {
			$response->write($icon);
			$response = $response->withHeader('Content-Type', 'image/'.pathinfo($url, PATHINFO_EXTENSION));
		}

	    return $response;
		
	});

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
			return !$clazz->evolvesFrom()->first();
		});

		$this->view->render($response, 'create.twig', [ 'starters' => $starters ]);
	
	})->add(new NeedsAuthentication($container['view'], 'create_chars'));

	/*
		Used by 'actions.php' to register to user input actions.
		These are handled via post-requests
	*/

	include_once 'actions.php';
	include_once 'admin.php';
	include_once 'editor.php';
	include_once 'account.php';

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

	$app->run();
?>
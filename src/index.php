<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;
	use DI\Container;
	use Slim\Factory\AppFactory;
	use Slim\Views\TwigMiddleware;
    use Slim\Views\Twig;
    use \Twig\TwigFunction;
    use \Twig\TwigFilter;

	require 'vendor/autoload.php';

	include_once "config.php";
	include_once "database/database.php";
	include_once "database/validation.php";
	include_once "localisation.php";
	include_once "helper.php";

	session_start();

	$container = new Container();

	$container->set('view', function () {
		$twig = Twig::create('templates');

	    //$basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
	    //$view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

        $environment = $twig->getEnvironment();

	    $environment->addFunction(new TwigFunction ('langs', function () {
	        return ['en', 'de', 'fr', 'it', 'cyber'];
	    }));

	    $environment->addFunction(new TwigFunction ('uri', function () {
	        return $_SERVER['REQUEST_URI'];
	    }));

		$environment->addFilter(new TwigFilter('roman', function ($number) {
			if(is_numeric($number)) {
				if(getLang() == 'cyber') return '0x'.str_pad(decbin($number), 4, '0', STR_PAD_LEFT);
				return toRoman($number);
			}
			return '?';
	    }));


		$environment->addFilter(new TwigFilter('removeCamel', function ($camel) {
			$camel = preg_replace('/([A-Z])/', ' $1', $camel);
			$camel = preg_replace('/_([a-zA-Z])/', ' $1', $camel);
			return ucfirst($camel);
	    }));

	    $environment->addFunction(new TwigFunction ('format', function ($key, $vars = []) {
	    	if(is_string($vars)) $vars = [ $vars ];
			return format($key, $vars);
	    }));

	    $environment->addFunction(new TwigFunction ('styles', function () {
	    	$lang = getLang();
	    	$general = glob("assets/css/*.css");
	    	$lang = $lang ? glob("assets/css/$lang/*.css") : [];
			return array_merge($general, $lang);
	    }));

	    $environment->addFunction(new TwigFunction ('scripts', function () {
	    	$lang = getLang();
	    	$general = glob("assets/scripts/*.js");
	    	$lang = $lang ? glob("assets/scripts/$lang/*.js") : [];
			return array_merge($general, $lang);
	    }));

	    /*
		    Used in templates to access a certain icon (for example of a class or an item)
		    or return the 'missing.png' image 
	    */
	    $environment->addFunction(new TwigFunction ('icon', function ($path, $color = null, $srcOnly = false) {
			return createIcon($path, $color, $srcOnly);
	    }));

	    $environment->addFilter(new TwigFilter('icon', function ($object, $srcOnly = false) {

	    	if(method_exists($object, 'icon')) {
	    		if(method_exists($object, 'color')) {
	    			$color = $object->color();
	    			if($color) return createIcon($object->icon(), $color, $srcOnly);
	    		} 
	    		return createIcon($object->icon(), null, $srcOnly);
	    	}
			
			return createIcon(null, null, $srcOnly);

	    }));

	    $environment->addFunction(new TwigFunction ('account', function () {
	        return getAccount();
	    }));

	    $environment->addFilter(new TwigFilter('all', function ($class) {
	        if(is_subclass_of($class, 'BaseModel')) return $class::all();
	        return collect([]);
	    }));

	    $environment->addFilter(new TwigFilter('call', function ($class, $method) {
	        return $class->$method();
	    }));

	    $environment->addFilter(new TwigFilter('class', function ($object) {
	        return (new \ReflectionClass($object))->getShortName();
	    }));

	    $environment->addFilter(new TwigFilter('byName', function ($class, $name) {
	        if(is_subclass_of($class, 'BaseModel')) return $class::where('name', $name)->first();
	        return null;
	    }));

	    $environment->addFilter(new TwigFilter('age', function ($time) {
	        $time = strtotime($time);
	        $age = round(abs($time - time()) / 24 / 60 / 60);
	        return format('time.'.($age > 1 ? 'days' : 'day'), [ $age ]);
	    }));

	    return $twig;
	});
	
	AppFactory::setContainer($container);
	$app = AppFactory::create();
	$app->add(TwigMiddleware::createFromContainer($app));

	/* The Home page, acting as the game screen */
	$app->get('/', function(Request $request, Response $response, array $args) {

		$selected = getAccount()->selected;

		if($selected) {
			if($selected->participant->battle) 
				return $this->get('view')->render($response, 'battle.twig', ['battle' => $selected->participant->battle]);
			return $this->get('view')->render($response, 'home.twig', []);
		}

		return $response->withRedirect('/profile');
		
	})->add(new NeedsAuthentication($container->get('view'), 'user'));

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
		$this->get('view')->render($response, 'map.twig', ['areas' => Area::all(), 'expanded' => $expanded]);
		
	})->add(new NeedsAuthentication($container->get('view'), 'user'));

	$app->get('/login', function (Request $request, Response $response, array $args) {
		$this->get('view')->render($response, 'login.twig', []);
	});

	$app->get('/profile', function (Request $request, Response $response, array $args) {
		$this->get('view')->render($response, 'profile.twig', []);
	})->add(new NeedsAuthentication($container->get('view'), 'user'));

	$app->get('/profile/create', function (Request $request, Response $response, array $args) {

		$starters = Clazz::all()->filter(function($clazz, $i) {
			return !$clazz->evolvesFrom()->first();
		});

		$this->get('view')->render($response, 'create.twig', [ 'starters' => $starters ]);
	
	})->add(new NeedsAuthentication($container->get('view'), 'create_chars'));

	/*
		Used by 'actions.php' to register to user input actions.
		These are handled via post-requests
	*/

	include_once 'actions.php';
	include_once 'admin.php';
	include_once 'editor.php';
	include_once 'account.php';

	$container->set('notFoundHandler', function ($container) {
	    return function (Request $request, Response $response) use ($container) {
	        return $container->view->render($response->withStatus(404), 'handlers/404.twig');
	    };
	});

    $container->set('errorHandler', function ($container) {
        return function (Request $request, Response $response, $exception) use ($container) {
            return $container->get('view')->render($container->get('response')->withStatus(500), 'handlers/500.twig', ['error' => $exception]);
        };
    });
    
    $container->set('phpErrorHandler', function ($container) {
        return function (Request $request, Response $response, $exception) use ($container) {
            return $container->get('view')->render($container->get('response')->withStatus(500), 'handlers/500.twig', ['error' => $exception]);
        };
    });

	$app->run();

<?php

function endsWith($haystack, $needle)
{
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}

	return (substr($haystack, -$length) === $needle);
}

function toRoman($number)
{
	$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	$returnValue = '';
	while ($number > 0) {
		foreach ($map as $roman => $int) {
			if ($number >= $int) {
				$number -= $int;
				$returnValue .= $roman;
				break;
			}
		}
	}
	return $returnValue;
}

function createIcon($path = null, $color = null, $srcOnly = false)
{
	global $prefer_svg;

	if ($path) {
		$path = strtolower(str_replace(' ', '_', $path));
		if (endsWith($path, '/random')) {

			$dir = str_replace('/random', '', $path);

			$all = scandir('assets/img/' . $dir);
			$all = array_filter($all, function ($val) use ($all) {
				return endsWith($val, '.png') || endsWith($val, '.svg');
			});

			if (!empty($all)) $img = $dir . '/' . $all[array_rand($all)];
		} else {

			if (file_exists("assets/img/$path.png")) $img = $path . '.png';
			if (file_exists("assets/img/$path.svg") && ($prefer_svg || !isset($img))) $img = $path . '.svg';
		}
	}

	$title = '';
	if (!isset($img)) {
		$img = 'missing.png';
		if (getAccount()->hasPermission('tester')) $title = $path;
	}

	$html = "<div title='$title' class='icon-container'>";

	$img = 'assets/img/' . $img;
	if ($srcOnly) return $img;

	$icon = "<img class='icon' src='/$img'></img>";
	$html .= $icon;

	if ($color) {
		$style = "mask-image: url($img); -webkit-mask-image: url($img); background-color: $color;";
		$colored = "<div class='colored' style='$style'></div>";
		$html .= $colored;
	}

	return $html . '</div>';
}

function redirect($request, $response, $path)
{
	//$routeContext = Slim\Routing\RouteContext::fromRequest($request);
	//$url = $routeContext->getRouteParser()->urlFor($path);
	return $response
		->withHeader('Location', $path)
		->withStatus(302);
}

/* Used by pages requiring a certain status like admin or betatester */
class NeedsAuthentication
{
	private $view;
	private $permission;
	private $post;
	private $responseFactory;

	public function __construct(\Slim\Views\Twig $view, $name = 'user', $post = false)
	{
		$this->view = $view;
		$permission = Permission::where('name', $name)->first() ?? null;
		$this->permission = $permission;
		$this->post = $post;
		$this->responseFactory = new \Slim\Psr7\Factory\ResponseFactory();
	}
	public function __invoke($request, $handler)
	{
		$account = getAccount();

		if ($account == null) {
			if ($this->post) return json_encode(['success' => false, 'message' => 'You are not logged in']);
			$url = '/login?next=' . $request->getUri()->getPath();
			return redirect($request, $this->responseFactory->createResponse(), $url);
		}

		if (!($this->permission && $account->hasPermission($this->permission))) {
			$response = $this->responseFactory->createResponse();
			if ($this->post) return json_encode(['success' => false, 'message' => 'You are not allowed to do this']);
			return $this->get('view')->render($response->withStatus(403), 'handlers/403.twig');
		}

		return $handler->handle($request);
	}
}

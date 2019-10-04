<?php

	function endsWith($haystack, $needle) {
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}

	function toRoman($number) {
	    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	    $returnValue = '';
	    while ($number > 0) {
	        foreach ($map as $roman => $int) {
	            if($number >= $int) {
	                $number -= $int;
	                $returnValue .= $roman;
	                break;
	            }
	        }
	    }
	    return $returnValue;
	}

	function createIcon($path = null, $color = null, $srcOnly = false) {
		
		if($path) {
			$path = strtolower(str_replace(' ', '_', $path));
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
		}

		$title = '';
        if(!isset($img)) {
			$img = 'missing.png';
			if(getAccount()->hasPermission('tester')) $title = $path;
        }

        $html = "<div title='$title' class='icon-container'>";
		
		$img = '/assets/img/'.$img;
		if($srcOnly) return $_SERVER['HTTP_HOST'].$img;

        $icon = "<img class='icon' src='$img'></img>";
        $html .= $icon;

		if($color) {
			$style = "mask-image: url($img); -webkit-mask-image: url($img); background-color: $color;";
			$colored = "<div class='colored' style='$style'></div>";
			$html .= $colored;
		}

		return $html.'</div>';
	}

	/* Used by pages requiring a certain status like admin or betatester */
	class NeedsAuthentication {
	    private $view;
	    private $permission;
	    private $post;

	    public function __construct(\Slim\Views\Twig $view, $name = 'user', $post = false) {
	        $this->view = $view;
	        $permission = Permission::where('name', $name)->first() ?? null;
	        $this->permission = $permission;
	        $this->post = $post;
	    }
	    public function __invoke($request, $response, $next) {
			
			$account = getAccount();
			
	        if ($account == null) {
	        	if($this->post) return json_encode(['success' => false, 'message' => 'You are not logged in']);
	            return $response->withRedirect('/login?next=' . $request->getUri()->getPath());
	        }

	        if (!($this->permission && $account->hasPermission($this->permission))) {
	        	if($this->post) return json_encode(['success' => false, 'message' => 'You are not allowed to do this']);
	            return $this->view->render($response->withStatus(403), 'handlers/403.twig');
	        }
	        return $next($request, $response);
	    }
	}

?>
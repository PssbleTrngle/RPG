<?php

	$default = 'en';

	function getLang() {
		global $default;
		
		if(isset($_SESSION['lang'])) return $_SESSION['lang'];
		return $default;

	}

	function setLang($lang) {
		$_SESSION['lang'] = $lang;
		return true;
	}

	function format($key, $vars, $d = false) {
		global $default;
		$lang = getLang();

		$file = 'lang/'.($d ? $default : $lang).'.json';

		if(file_exists($file)) {

			$json = json_decode(file_get_contents($file), true);

			foreach(explode('.', strtolower($key)) as $subKey) {
				if(array_key_exists($subKey, $json))
					$json = $json[$subKey];

				else if(!$d) return format($key, $vars, true);
				else return '???';
			}

			if($vars)
				foreach($vars as $key => $var)
					$json = str_replace('$'.($key+1), $var, $json);

			return $json;

		}

		return '???';

	}

?>
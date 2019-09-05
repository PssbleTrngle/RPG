<?php

	$default = 'en';
	$lang = 'de';

	function format($key, $vars, $d = false) {
		global $default;
		global $lang;

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
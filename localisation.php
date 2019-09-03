<?php

	$default = 'en';
	$lang = 'de';

	function format($key, $vars, $d = false) {
		global $default;
		global $lang;

		$file = 'lang/'.($d ? $default : $lang).'.json';

		if(file_exists($file)) {

			$json = json_decode(file_get_contents($file), true);

			foreach(explode('.', $key) as $subKey) {
				if(array_key_exists($subKey, $json))
					$json = $json[$subKey];

				else if($lang != $default) return format($key, $vars, true);
			}

			if($vars)
				foreach($vars as $key => $var)
					$json = str_replace('$'.($key+1), $var, $json);

			return $json;

		}

		return '???';

	}

?>
<?php

	$formats = [
		'h' => 'highlight',
	];

	function convert($json) {
		if(is_string($json)) return $json;

		$array = [];
		foreach($json as $key => $value)
			if(is_array($value))
				foreach(convert($value) as $subkey => $subvalue)
					$array[$key.'.'.$subkey] = $subvalue;
			else $array[$key] = $value;

		return $array;

	}

	function readLangFiles($lang, $default) {

		$json = [];

		foreach([$default, $lang] as $l) {

			$file = 'lang/'.$l.'.json';
			if(file_exists($file)) {
				$array = convert(json_decode(file_get_contents($file), true));
				foreach ($array as $key => $value)
					$json[$key] = $value;
			}

		}

		return $json;

	}

	$json = null;
	$lang = null;

	function getLang() {
		global $lang;
		global $default;
		
		if(!is_null($lang)) return $lang;
		if($_SESSION['lang'] ?? null) {
			$lang = $_SESSION['lang'];
			return $lang;
		}

		setLang($default);
		return $default;

	}

	function setLang($lang) {
		$_SESSION['lang'] = $lang;
		return true;
	}

	function format($key, $vars = []) {
		global $json;
		global $formats;
		$key = strtolower($key);

		if(is_null($json))
			$json = readLangFiles(getLang(), option('default_lang'));

		$unknown = getAccount()->hasStatus('betatester') ? $key : '???';
		$translation = $json[$key] ?? $unknown;

		foreach ($formats as $key => $class) {
			$pattern = "/§$key(.*?)( |$)/";
			$translation = preg_replace($pattern, "<span class='$class'>$1</span> ", $translation);
		}

		if($vars)
			foreach($vars as $key => $var)
				$translation = str_replace('$'.($key+1), $var, $translation);

		return $translation;

	}

?>
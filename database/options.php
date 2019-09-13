<?php

	/*
		These are some game constants,
		localized here to be ease to alter
		and accessed in code through the function below
	*/

	$OPTIONS = [
	
		'max_enemies' => 4,
		'call_chance' => 0.03,
		'base_bag_size' => 10,
		'default_lang' => 'en',
		'max_effects' => 3,
		
	];

	function option($name) {
	
		global $OPTIONS;
		return $OPTIONS[$name];
		
	}

?>
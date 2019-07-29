<?php

	/*
		These are some game constants,
		localized here to be ease to alter
		and accessed in code through the function below
	*/

	$OPTIONS = [
	
		'max_enemies' => 4,
		'call_chance' => 0.02,
		'base_bag_size' => 10,
		
	];

	function option($name) {
	
		global $OPTIONS;
		return $OPTIONS[$name];
		
	}

?>
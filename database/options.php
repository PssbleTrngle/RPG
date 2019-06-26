<?php

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
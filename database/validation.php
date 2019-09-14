<?php

	/*
		Only used for a admin interface for now
	*/

	include_once "database.php";

	function info($text, $class = 'primary') {
		return "<p class='text-$class'>$text</p>";
	}

	function validate($debug_level = 0b00) {
		$out = "";

		foreach(get_declared_classes() as $class) {
			if(is_subclass_of($class, 'BaseModel') && method_exists($class, 'validate')) {
				$corrected = 0;

				foreach ($class::all() as $instance) {
					if(!$instance->validate()) $corrected++;
				}

				if(hasLevel($debug_level, 1) && $corrected > 0) $out .= info($corrected.' '.$class.' not correct', 'red');
				else if(hasLevel($debug_level, 0) && $corrected == 0) $out .= info('Every '.$class.' correct', 'green');

			}
		}

		$out .= '<br>';
		
		$out .= validateClasses($debug_level);
		$out .= validateRaces($debug_level);

		return $out;
	}

	function validateRaces($debug_level) {
		$out = "";
	
		foreach(Race::all() as $race) {
			$total = $race->stats->total();
			$reference = 30;
			
			if($total != $reference) {
				if(hasLevel($debug_level, 1)) $out .= info($race->name.' total stats are not  '.$reference.' ('.$total.')', 'red');
			} else
				if(hasLevel($debug_level, 0)) $out .= info($race->name.' total stats are correct', 'green');
		
		}
		
		return $out."<br>";
		
	}

	function validateClasses($debug_level) {
		$out = "";
	
		foreach(Clazz::all() as $class) {
			$total = $class->stats->total();
			$rank = $class->rank();
			$per_rank = 10;
			$reference = 100 + $per_rank * ($rank-1);
			
			if($total != $reference) {
				if(hasLevel($debug_level, 1)) $out .= info($class->name.' total stats are not  '.$reference.' ('.$total.')', 'red');
			} else 
				if(hasLevel($debug_level, 0)) $out .= info($class->name.' total stats are correct', 'green');
		
		}
		
		return $out."<br>";
	}

	function hasLevel($debug_level, $bit) {
		
		$mask = 1 << $bit;
		return ($mask & $debug_level) >> $bit;
	}

?>
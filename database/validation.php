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
					try {
						if(!$instance->validate()) $corrected++;
					} catch(Exception $e) {
						$corrected++;
					}
				}

				if(hasLevel($debug_level, 1) && $corrected > 0) $out .= info($corrected.' '.$class.' not correct', 'red');
				else if(hasLevel($debug_level, 0) && $corrected == 0) $out .= info('Every '.$class.' correct', 'green');

			}
		}

		$out .= '<br>';
		
		$out .= validateEvolutions($debug_level);
		$out .= validateStats($debug_level);

		return $out;
	}

	function validateEvolutions($debug_level) {
		$out = "";

		$lastRank = 1;
	
		foreach(Clazz::all() as $class) {
			$evolutions = $class->evolvesTo->count();;
			$rank = $class->rank();

			if($rank != $lastRank) $out .= '<br>';
			$lastRank = $rank;

			switch($rank) {
				case 1: $reference = 3; break;
				case 2: $reference = 2; break;
				case 3: $reference = 2; break;
				case 4: $reference = 0; break;
			}

			$needs = $reference - $evolutions;
			
			if($needs < 0) {
				if(hasLevel($debug_level, 1)) $out .= info('['.$class->id.'] '.$class->name.' has '.abs($needs).' evolutions to much', abs($needs) == 1 ? 'yellow' : 'red');
			} else if($needs > 0) {
				if(hasLevel($debug_level, 1)) $out .= info('['.$class->id.'] '.$class->name.' has '.abs($needs).' evolutions to less', abs($needs) == 1 ? 'yellow' : 'red');
			} else 
				if(hasLevel($debug_level, 0)) $out .= info('['.$class->id.'] '.$class->name.' evolutions are correct ('.$evolutions.')', 'green');
		
		}
		
		return $out."<br>";
		
	}

	function validateStats($debug_level) {
		$out = '';
	
		foreach(Clazz::all() as $class) {
			$total = $class->stats->total();
			$rank = $class->rank();
			$reference = $rank == 1 ? 100 : 10;
			
			if($total != $reference) {
				if(hasLevel($debug_level, 1)) $out .= info($class->name.' total stats are not  '.$reference.' ('.$total.')', 'red');
			} else 
				if(hasLevel($debug_level, 0)) $out .= info($class->name.' total stats are correct', 'green');
		
		}

		$out .= '<br>';
	
		foreach(Race::all() as $race) {
			$total = $race->stats->total();
			$reference = 30;
			
			if($total != $reference) {
				if(hasLevel($debug_level, 1)) $out .= info($race->name.' total stats are not  '.$reference.' ('.$total.')', 'red');
			} else
				if(hasLevel($debug_level, 0)) $out .= info($race->name.' total stats are correct', 'green');
		
		}
		
		return $out.'<br>';
	}

	function hasLevel($debug_level, $bit) {
		
		$mask = 1 << $bit;
		return ($mask & $debug_level) >> $bit;
	}

?>
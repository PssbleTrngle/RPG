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
		
		$out .= validateClasses($debug_level);
		$out .= validateRaces($debug_level);
		$out .= validateBattles($debug_level);
		$out .= validateAccounts($debug_level);
		
		return $out;
	}

	function validateBattles($debug_level) {

		$removed = 0;
		$removedParticipants = 0;

		foreach(Battle::all() as $battle) {
			
			$won = true;
			foreach($battle->enemies() as $enemy)
				$won &= $enemy->health <= 0;
			
			if($won) {
				$battle->win();
				$removed++;
			}
		
		}

		foreach(Participant::all() as $participant) {
			
			if($participant->enemy_id && !$participant->battle) {
				$participant->enemy->delete();
				$participant->delete();
				$removedParticipants++;
			}
		
		}

		if($remove > 0 && hasLevel($debug_level, 1))
			return $removed.' battles removed';
		if($remove == 0 && hasLevel($debug_level, 0))
			return 'all battles correct';

		if($removedParticipants > 0 && hasLevel($debug_level, 1))
			return $removed.' participants removed';
		if($removedParticipants == 0 && hasLevel($debug_level, 0))
			return 'all participant correct';

	}

	function validateAccounts($debug_level) {

		foreach (Account::all() as $account) {

			if($account->selected && $account->id != $account->selected->account_id) {
				$account->selected_id = null;
				$account->save();
			}
			
			foreach($account->characters as $character) {
				if(!$character->position) $character->createPosition();
				if(!$character->participant) $character->createParticipant();
			}

		}
		return '';

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
<?php

	abstract class ParticipantModel extends BaseModel {

		public abstract function stats();

		public abstract function level();

		public abstract function maxHealth();

		public function takeTurn() {}

		public function loose() {}

		public function win() {}
		
		public function addLoot(array $loot) {}
		
		public function getLoot() {
			return ['items' => [], 'xp' => 0];
		}

		public function participant() {
			return $this->belongsTo(Participant::class, 'participant_id');
		}

	}

?>
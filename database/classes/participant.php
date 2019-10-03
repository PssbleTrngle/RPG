<?php

	abstract class ParticipantModel extends BaseModel {

		public abstract function stats();

		public abstract function level();

		public abstract function maxHealth();

		public function takeTurn() {}

		public function loose() {}

		public function win() {}
		
		public function addLoot(Loot $loot) {}
		
		public function getLoot() {
			return new Loot([]);
		}

		public function participant() {
			return $this->belongsTo(Participant::class, 'participant_id');
		}

		public function getBattle() {
			return $this->participant->battle;
		}

	}

?>
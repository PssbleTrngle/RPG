<?php

	class Loot {

		public $items;
		public $xp;

		public function __construct($items, $xp = 0) {
			$this->items = $items;
			$this->xp = $xp;
		}

		public function add(Loot $other) {
			foreach($other->items as $item)
				$this->items[] = $item;

			$this->xp += $other->xp;
		}

	}

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

	}

?>
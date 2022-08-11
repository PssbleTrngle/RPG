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

?>
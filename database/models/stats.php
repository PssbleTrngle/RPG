<?php

	class Stats extends BaseModel {
   		
		protected $table = 'stats';
		public $keys = array('wisdom', 'strength', 'resistance', 'agility', 'luck');

		public function __construct() {
			$this->id = -1;
		}

		public function save(array $options = []) {
			if($this->id > 0) parent::save($options);
		}
		
		public function total() {
			
			$total = 0;
			foreach($this->keys as $stat)
				$total += $this->$stat;
			
			return $total;
		}
  
		public function add($other) {
			$stats = new Stats;		
			foreach($this->keys as $stat)
				$stats->$stat = $this->$stat + $other->$stat;
			
			return $stats;
			
		}

		public function statFactor($name) {

			return 0.2;
			return $this->$name / 100;

		}

		public function apply($value, $stat, $negative = false) {

			if($negative)
				$by = 1 + $this->statFactor($stat);
			else
				$by = 1 - $this->statFactor($stat);


			return floor($value * $by);

		}
		
	}

?>
<?php

	class DamageEvent {

		public $amount;
		public $source;
		public $element;

		public function __construct($amount, $source = null, $element = null) {
			$this->amount = abs($amount);
			$this->source = $source;
			$this->element = $element;
		}

	}

?>
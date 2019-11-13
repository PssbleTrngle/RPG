<?php

	class Vec2i {

		public $x;
		public $y;

		public function __construct(int $x, int $y) {
			$this->x = $x;
			$this->y = $y;
		}

		public function add($arg1, int $arg2 = 0) {

			if(is_numeric($arg1))
				return new Vec2i($this->x + $arg1, $this->y + $arg2);

			if(is_a($arg1, 'Vec2i'))
				return new Vec2i($this->x + $arg1->x, $this->y + $arg1->y);

			return false;		

		}

	}

?>
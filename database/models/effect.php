<?php

	class Effect extends BaseModel {
   		
		protected $table = 'effect';
		
		public static $functions = array();

		/* TODO remove */
		public function name() {
			return $this->name;
		}
			
		public function apply($target) {
			$func = Skill::$functions[$this->id];
			
			if(!$func) return false;
			
			return $func($target);
		}
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => 'poison'], ['apply' => function($target) {
				if(method_exists($target, 'damage')) {
					$damage = 2;
					return $target->damage($damage);
				}
				return false;
			}]);
			
			static::register(['id' => 2, 'name' => 'rage'], ['apply' => function($target) {
				
			}]);
			
			static::register(['id' => 3, 'name' => 'burn'], ['apply' => function($target) {
				
			}]);
			
			static::register(['id' => 4, 'name' => 'frozen'], ['apply' => function($target) {
				
			}]);
			
			static::register(['id' => 5, 'name' => 'shock'], ['apply' => function($target) {
				
			}]);
			
		}
		
	}	

?>
<?php

	class Effect extends BaseModel {
   		
		protected $table = 'effect';
		
		public static $functions = array();
			
		public function apply($target) {
			$func = Skill::$functions[$this->id];
			
			if(!$func) return false;
			
			return $func($target);
		}
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => 'Poison'], ['apply' => function($target) {
				if(method_exists($target, 'damage')) {
					$damage = 2;
					return $target->damage($damage) ? true : false;
				}
				return false;
			}]);
			
		}
		
	}	

?>
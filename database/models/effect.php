<?php

	class Effect extends BaseModel {
   		
		protected $table = 'effect';
		
		public static $functions = array();

		/* TODO remove */
		public function name() {
			return $this->name;
		}
			
		public function apply(Target $target) {
			global $capsule;

			$func = static::$functions['apply'][$this->id];
			
			if(is_a($target, 'Participant')) {

				$countdown = $target->effects->where('id', $this->id)->first()->pivot->countdown;
				if($countdown == 0) {
					$target->removeEffect($this);
					return true;
				}
			
				$capsule::table('participant_effects')
					->where('participant_id', $target->id)
					->where('effect_id', $this->id)
					->decrement('countdown');

			}

			if(!$func) return false;
			
			return $func($target);
		}
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => 'poison', 'fade_min' => 5, 'fade_max' => 7, 'block' => false], ['apply' => function(Target $target) {
				$damage = 4;
				return $target->damage($damage);
			}]);
			
			static::register(['id' => 2, 'name' => 'stunned', 'fade_min' => 1, 'fade_max' => 2, 'block' => true]);
			
			static::register(['id' => 3, 'name' => 'burned', 'fade_min' => 2, 'fade_max' => 4, 'block' => false], ['apply' => function(Target $target) {
				$damage = 2;
				return $target->damage($damage);
			}]);
			
			static::register(['id' => 4, 'name' => 'frozen', 'fade_min' => 3, 'fade_max' => 4, 'block' => true]);
			
			static::register(['id' => 5, 'name' => 'rage', 'fade_min' => 2, 'fade_max' => 4, 'block' => false], ['apply' => function(Target $target) {
				
			}]);
			
		}
		
	}	

?>
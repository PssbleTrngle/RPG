<?php

	class Skill extends BaseModel {
   		
		protected $table = 'skill';
		
		public function timeout($character) {
			if(!$character) return false;
			global $capsule;
			
			if($character) {
			
				$capsule::table('character_skills')
					->where('character_id', $character->id)
					->where('skill_id', $this->id)
					->update(['nextUse' => $this->timeout]);
				
			}			
		}
			
		public function apply($target, $user) {
			$func = static::$functions['use'][$this->id];
			
			if(!$func) return false;
			
			return $func($target, $user);
		}
	
		public static function registerAll() {

			/* ------------------------  ATTACKS  ------------------------ */
			
			static::register(['id' => 1, 'name' => 'Slash', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function($target, $user) {
				if(method_exists($target, 'damage')) {
					$damage = $user->stats()->apply(8, 'strength');
					return $target->damage($damage) ? 'The slash dealt '.$damage.' damage to '.$target->name() : 'The attack had no effect!';
				}
				return false;
			}]);
			
			static::register(['id' => 2, 'name' => 'Backstab', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function($target, $user) {
				if(method_exists($target, 'damage')) {
					$damage = $user->stats()->apply(8, 'strength');

					if(rand(1, 100) < 0.1 && method_exists($target, 'addEffect')) {
						$target->addEffect(Effect::where('name', 'poison'));
					}

					return $target->damage($damage) ? 'The backstab dealt '.$damage.' damage to '.$target->name() : 'The attack had no effect!';
				}
				return false;
			}]);

			/* ------------------------  HEALING  ------------------------ */
			
			static::register(['id' => 51, 'name' => "Heal", 'timeout' => 0, 'cost' => 2, 'group' => false], ['use' => function($target, $user) {
				if(method_exists($target, 'heal')) {
					$health = $user->stats()->apply(15, 'wisdom');
					return $target->heal($health) ? 'You healed '.$target->name().' by '.$health : 'The spell failed!';
				}
				return false;
			}]);
			
			static::register(['id' => 52, 'name' => "Cleansing Rain", 'timeout' => 0, 'cost' => 2, 'group' => true], ['use' => function($target, $user) {
				$healt = 0;
				foreach($targets as $target) {
					if(method_exists($target, 'heal')) {
						$health = $user->stats()->apply(8, 'wisdom');
						if($target->heal($health)) $healt++;
					}
				}
				return $healt > 0 ? 'The rain cleansed '.$healt : false;
			}]);

			/* ------------------------  ATTACK SPELLS  ------------------------ */
			
			static::register(['id' => 101, 'name' => 'Pulse', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function($target, $user) {			

				if(method_exists($target, 'damage')) {
					$damage = $user->stats()->apply(8, 'wisdom');
					return $target->damage($damage) ? 'The pulse dealt '.$damage.' damage to '.$target->name() : 'The attack had no effect!';
				}
				return false;
			}]);
			
			static::register(['id' => 102, 'name' => 'Rumble', 'timeout' => 0, 'cost' => 1, 'group' => true], ['use' => function($target, $user) {
				$damaged = 0;
				foreach($targets as $target) {
					if(method_exists($target, 'damage')) {
						$damage = $user->stats()->apply(4, 'wisdom');
						if($target->damage($damage)) $damaged++;
					}
				}
				return $damaged > 0 ? 'The rumble damaged '.$damaged : false;
			}]);

			/* ------------------------  MISC  ------------------------ */
			
			static::register(['id' => 500, 'name' => 'Glow', 'timeout' => 0, 'cost' => 1, 'group' => true], ['use' => function($target, $user) {}]);
			
		}
		
	}	

?>
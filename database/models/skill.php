<?php

	use Illuminate\Support\Collection as Collection;

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
	
		public static function registerAll() {

			/* ------------------------  ATTACKS  ------------------------ */
			
			static::register(['id' => 1, 'name' => 'slash', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function(Skill $skill, Target$target, Participant $user) {

				$damage = $user->stats()->apply(8, 'strength');
				return $target->damage(new DamageEvent($damage)) ? 'The slash dealt '.$damage.' damage to '.$target->name() : 'The attack had no effect!';

			}]);
			
			static::register(['id' => 2, 'name' => 'backstab', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function(Skill $skill, Target$target, Participant $user) {
				
				$damage = new DamageEvent($user->stats()->apply(8, 'strength'));

				if(rand(1, 100) < 0.1)
					$target->addEffect(Effect::where('name', 'poison'));

				return $target->damage($damage) ? new Message('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]) : 'no_effect';
				
			}]);

			/* ------------------------  HEALING  ------------------------ */
			
			static::register(['id' => 51, 'name' => "heal", 'timeout' => 0, 'cost' => 2, 'group' => false], ['use' => function(Skill $skill, Target$target, Participant $user) {

				$health = $user->stats()->apply(15, 'wisdom');
				return $target->heal($health) ? 'You healed '.$target->name().' by '.$health : 'The spell failed!';
			
			}]);
			
			static::register(['id' => 52, 'name' => "cleansing Rain", 'timeout' => 0, 'cost' => 2, 'group' => true], ['use' => function(Skill $skill, Collection$target, Participant $user) {
				$healt = 0;
				foreach($targets as $target) {

					$health = $user->stats()->apply(8, 'wisdom');
					if($target->heal($health)) $healt++;
					
				}
				return $healt > 0 ? 'The rain cleansed '.$healt : false;
			}]);

			static::register(['id' => 53, 'name' => "revive", 'timeout' => 0, 'cost' => 5, 'group' => false, 'affectDead' => true], ['use' => function(Skill $skill, Target$target, Participant $user) {

				return $target->revive($user) ? 'You revived '.$target->name() : 'The spell failed!';
			
			}]);

			/* ------------------------  ATTACK SPELLS  ------------------------ */
			
			static::register(['id' => 101, 'name' => 'pulse', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function(Skill $skill, Target$target, Participant $user) {

				$damage = new DamageEvent($user->stats()->apply(8, 'wisdom'));
				return $target->damage($damage) ? new Message('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]) : 'no_effect';
				
			}]);
			
			static::register(['id' => 102, 'name' => 'rumble', 'timeout' => 0, 'cost' => 1, 'group' => true], ['use' => function(Skill $skill, Collection$target, Participant $user) {
				$damaged = 0;
				foreach($targets as $target) {

					$damage = $user->stats()->apply(4, 'wisdom');
					if($target->damage(new DamageEvent($damage))) $damaged++;
						
				}
				return $damaged > 0 ? 'The rumble damaged '.$damaged : false;
			}]);

			/* ------------------------  MISC  ------------------------ */
			
			static::register(['id' => 500, 'name' => 'glow', 'timeout' => 0, 'cost' => 2, 'group' => true], ['use' => function(Skill $skill, Collection$target, Participant $user) {}]);
			
		}
		
	}	

?>
<?php

	use Illuminate\Support\Collection as Collection;

	class Skill extends BaseModel {
   		
		protected $table = 'skill';
		
		public function timeout(Participant $participant) {
			global $capsule;
			
			if($participant) {
			
				$capsule::table('participant_skills')
					->where('participant_id', $participant->id)
					->where('skill_id', $this->id)
					->update(['nextUse' => $this->timeout]);
				
			}			
		}
	
		public static function registerAll() {

			global $capsule;
			$capsule->table('class_skills')->where('class_id', '>', 0)->delete();
			$relations = [];

			/* ------------------------  ATTACKS  ------------------------ */
			
			static::register(['id' => 1, 'name' => 'slash', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function(Skill $skill, Target $target, Participant $user) {

				$damage = new DamageEvent($user->stats()->apply(8, 'strength'));
				return $target->damage($damage) ? new Translation('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]) : 'no_effect';

			}]);
			
			static::register(['id' => 2, 'name' => 'backstab', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function(Skill $skill, Target $target, Participant $user) {
				
				$damage = new DamageEvent($user->stats()->apply(8, 'strength'));

				if(rand(1, 100) < 0.1)
					$target->addEffect(Effect::where('name', 'poison'));

				return $target->damage($damage) ? new Translation('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]) : 'no_effect';
				
			}]);

			/* ------------------------  HEALING  ------------------------ */
			
			static::register(['id' => 51, 'name' => "heal", 'timeout' => 0, 'cost' => 2, 'group' => false], ['use' => function(Skill $skill, Target $target, Participant $user) {

				$health = $user->stats()->apply(15, 'wisdom');
				return $target->heal($health) ? 'You healed '.$target->name().' by '.$health : 'The spell failed!';
			
			}]);
			
			static::register(['id' => 52, 'name' => "cleansing Rain", 'timeout' => 0, 'cost' => 2, 'group' => true], ['use' => function(Skill $skill, Target $target, Participant $user) {

				$health = $user->stats()->apply(8, 'wisdom');
				return $target->heal($health) ? 'You healed '.$target->name().' by '.$health : 'The spell failed!';

			}]);

			static::register(['id' => 53, 'name' => "revive", 'timeout' => 0, 'cost' => 5, 'group' => false, 'affectDead' => true], ['use' => function(Skill $skill, Target $target, Participant $user) {

				return $target->revive($user) ? 'You revived '.$target->name() : 'The spell failed!';
			
			}]);

			/* ------------------------  ATTACK SPELLS  ------------------------ */
			
			static::register(['id' => 101, 'name' => 'pulse', 'timeout' => 0, 'cost' => 1, 'group' => false], ['use' => function(Skill $skill, Target $target, Participant $user) {

				$damage = new DamageEvent($user->stats()->apply(8, 'wisdom'));
				return $target->damage($damage) ? new Translation('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]) : 'no_effect';
				
			}]);
			$relations[] = ['class_id' => 1, 'skill_id' => 101, 'level' => 0];
			
			static::register(['id' => 102, 'name' => 'rumble', 'timeout' => 0, 'cost' => 1, 'group' => true], ['use' => function(Skill $skill, Target $target, Participant $user) {

				$damage = new DamageEvent($user->stats()->apply(5, 'wisdom'));
				return $target->damage($damage) ? new Translation('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]) : 'no_effect';

			}]);
			$relations[] = ['class_id' => 1, 'skill_id' => 102, 'level' => 0];

			/* ------------------------  MISC  ------------------------ */
			
			static::register(['id' => 500, 'name' => 'glow', 'timeout' => 0, 'cost' => 2, 'group' => true], ['use' => function(Skill $skill, Target $target, Participant $user) {}]);

			/* ------------------------  ENEMIES  ------------------------ */

			$capsule->table('class_skills')->insert($relations);
			
		}
		
	}	

?>
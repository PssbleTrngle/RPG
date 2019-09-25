<?php

	function simpleDamage($damage, $effects = [], $stat = 'strength', $failMsg = 'no_effect') {

		return function(Skill $skill, Target $target, Participant $user) use($damage, $stat, $effects, $failMsg) {

				$damage = new DamageEvent($user->stats()->apply($damage, $stat));

				foreach($effects as $effect => $chance)
					if(rand(1, 100) <= $chance)
						$target->addEffect(Effect::where('name', $effect));

				if($target->damage($damage))
					return new Translation('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]);

				return $failMsg;

		};

	}

	function spellDamage($damage, $effects = [], $stat = 'wisdom', $failMsg = 'spell_failed') {
		return simpleDamage($damage, $effects, $stat, $failMsg);
	}

	function simpleHeal($health, $effects = [], $stat = 'wisdom', $failMsg = 'spell_failed') {

		return function(Skill $skill, Target $target, Participant $user) use($health, $stat, $effects, $failMsg) {

				$health = $user->stats()->apply(15, 'wisdom');

				foreach($effects as $effect => $chance)
					if(rand(1, 100) <= $chance)
						$target->addEffect(Effect::where('name', $effect));

				if($target->heal($health))
					return new Translation('healed_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]);

				return $failMsg;

		};

	}

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

			/* ------------------------  ATTACKS  ------------------------ */
			
			static::register(['id' => 1, 'name' => 'slash', 'timeout' => 0, 'cost' => 1, 'group' => false],
				['use' => simpleDamage(8)]);
			
			static::register(['id' => 2, 'name' => 'backstab', 'timeout' => 0, 'cost' => 1, 'group' => false],
				['use' => simpleDamage(8, ['poison' => 0.1])]);

			/* ------------------------  HEALING  ------------------------ */
			
			static::register(['id' => 51, 'name' => "heal", 'timeout' => 0, 'cost' => 2, 'group' => false],
				['use' => simpleHeal(15)]);
			
			static::register(['id' => 52, 'name' => "cleansing Rain", 'timeout' => 0, 'cost' => 2, 'group' => true],
				['use' => simpleHeal(8)]);

			static::register(['id' => 53, 'name' => "revive", 'timeout' => 0, 'cost' => 5, 'group' => false, 'affectDead' => true],
				['use' => function(Skill $skill, Target $target, Participant $user) {

				return $target->revive($user) ? 'You revived '.$target->name() : 'The spell failed!';
			
			}]);

			/* ------------------------  ATTACK SPELLS  ------------------------ */
			
			static::register(['id' => 101, 'name' => 'pulse', 'timeout' => 0, 'cost' => 1, 'group' => false],
				['use' => spellDamage(8)]);
			
			static::register(['id' => 102, 'name' => 'rumble', 'timeout' => 0, 'cost' => 1, 'group' => true],
				['use' => spellDamage(5)]);

			/* ------------------------  MISC  ------------------------ */
			
			static::register(['id' => 500, 'name' => 'glow', 'timeout' => 0, 'cost' => 2, 'group' => true]);

			/* ------------------------  ENEMIES  ------------------------ */
			
		}
		
	}	

?>
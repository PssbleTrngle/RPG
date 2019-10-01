<?php

	class Skill extends BaseModel {

		public static function simpleDamage($damage, $effects = [], $stat = 'strength', $failMsg = 'no_effect') {

			return function(Skill $skill, Target $target, Participant $user) use($damage, $stat, $effects, $failMsg) {

					$damage = new DamageEvent($user->stats()->apply($damage, $stat));

					foreach($effects as $effect => $chance)
						if(rand(1, 100) <= $chance * 100)
							$target->addEffect(Effect::where('name', $effect)->first());

					if($target->damage($damage))
						return new Translation('damaged_using', [$user->name(), $skill->name(), $damage->amount, $target->name()]);

					return $failMsg;

			};

		}

		public static function spellDamage($damage, $effects = [], $stat = 'wisdom', $failMsg = 'spell_failed') {
			return static::simpleDamage($damage, $effects, $stat, $failMsg);
		}

		public static function simpleHeal($health, $effects = [], $stat = 'wisdom', $failMsg = 'spell_failed') {

			return function(Skill $skill, Target $target, Participant $user) use($health, $stat, $effects, $failMsg) {

					$amount = $user->stats()->apply(15, 'wisdom');

					foreach($effects as $effect => $chance)
						if(rand(1, 100) <= $chance * 100)
							$target->addEffect(Effect::where('name', $effect)->first());

					if($target->heal($amount))
						return new Translation('healed_using', [$user->name(), $skill->name(), $amount, $target->name()]);

					return $failMsg;

			};

		}

		public static function areaHexagon($radius = 1, $centerX = 0, $centerY = 0) {

			return function(Skill $skill = null) use($radius, $centerX, $centerY) {

				$neighboors = [];

				for($x = -$radius; $x <= $radius; $x++)
					for($y = -$radius; $y <= $radius; $y++)
						if(abs($x + $y) <= $radius)
							$neighboors[] = ['x' => $centerX + $x, 'y' => $centerY + $y];

				return collect($neighboors);

			};

		}

		public static function areaStar($radius = 1, $centerX = 0, $centerY = 0) {

			return function(Skill $skill = null) use($radius, $centerX, $centerY) {

				$neighboors = [];

				for($x = -$radius; $x <= $radius; $x++) 
					if($x != $centerX)
						$neighboors[] = ['x' => $centerX + $x, 'y' => $centerY];

				for($y = -$radius; $y <= $radius; $y++) 
					if($y != $centerY)
						$neighboors[] = ['x' => $centerX, 'y' => $centerY + $y];

				for($y = -$radius; $y <= $radius; $y++) 
					if($y != $centerY)
						$neighboors[] = ['x' => $centerX - $y, 'y' => $centerY + $y];

				return collect($neighboors);

			};

		}

		public static function areaSingle() {

			return function(Skill $skill = null) {
				return [['x' => 0, 'y' => 0]];
			};

		}
   		
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

		function use($target, Participant $user, $charged = false) {
			global $capsule;

			if(!$target || !$user) return false;

			$battle = $user->battle;
			if($battle && is_a($target, 'Field')) {

				$battle->prepareTurn();

				$targets = $battle->fieldsIn($this->area(), $target->x, $target->y)->pluck('participant')->filter();
							
				if(!$this->affectDead)
					$targets = $targets->where('health', '>', '0');

				if($this->charge <= 0 || $charged) {
					/* Does not need charging or is charged */

					$success = false;
					foreach($targets as $target) {
						$message = $this->__call('use', [$target, $user]);
						$battle->addMessage($message);
						$success = $success || $message;
					}

					$battle->participants->fresh();

					if($success) {
						$this->timeout($user);
						if(!$charged) $battle->next();
					}

					return $success;

				} else {
					/* Start charging */
				
					$charging = ['skill_id' => $this->id, 'participant_id' => $user->id, 'field_id' => $target->id, 'countdown' => $this->charge];
					$capsule->table('charging_skills')->insert($charging);

					$battle->addMessage(Translation('started_charging', [$user->name(), $this->name()]));
					return true;

				}

			} else if(is_a($target, 'Target'))
				return $this->__call('use', [$target, $user]);

			return false;

		}
	
		public static function registerAll() {

			/* ------------------------  ATTACKS  ------------------------ */
			
			static::register(['id' => 1, 'name' => 'slash', 'timeout' => 0, 'cost' => 1, 'range' => 1],
				['use' => static::simpleDamage(8), 'area' => static::areaSingle()]);
			
			static::register(['id' => 2, 'name' => 'backstab', 'timeout' => 0, 'cost' => 1, 'range' => 1],
				['use' => static::simpleDamage(8, ['poison' => 0.1]), 'area' => static::areaSingle()]);

			/* ------------------------  HEALING  ------------------------ */
			
			static::register(['id' => 51, 'name' => "heal", 'timeout' => 0, 'cost' => 2, 'range' => 2],
				['use' => static::simpleHeal(15), 'area' => static::areaSingle()]);
			
			static::register(['id' => 52, 'name' => "cleansing_rain", 'timeout' => 0, 'cost' => 2, 'range' => 1],
				['use' => static::simpleHeal(8), 'area' => static::areaHexagon()]);

			static::register(['id' => 53, 'name' => "revive", 'timeout' => 0, 'cost' => 5, 'range' => 1, 'affectDead' => true],
				['use' => function(Skill $skill, Target $target, Participant $user) {

				return $target->revive($user) ? 'You revived '.$target->name() : 'The spell failed!';
			
			}, 'area' => static::areaSingle()]);

			/* ------------------------  ATTACK SPELLS  ------------------------ */
			
			static::register(['id' => 101, 'name' => 'pulse', 'timeout' => 0, 'cost' => 1, 'range' => 2],
				['use' => static::spellDamage(8), 'area' => static::areaSingle()]);
			
			static::register(['id' => 102, 'name' => 'rumble', 'timeout' => 0, 'cost' => 1, 'range' => 1],
				['use' => static::spellDamage(5), 'area' => static::areaHexagon()]);
			
			static::register(['id' => 103, 'name' => 'discharge', 'timeout' => 0, 'cost' => 1, 'range' => 2, 'charge' => 3],
				['use' => static::spellDamage(12, ['stunned' => 0.1]), 'area' => static::areaSingle()]);
			
			static::register(['id' => 120, 'name' => 'blast', 'timeout' => 0, 'cost' => 1, 'range' => 0, 'charge' => 1],
				['use' => static::spellDamage(6, ['burned' => 0.1]), 'area' => static::areaStar(2)]);
			
			static::register(['id' => 121, 'name' => 'kamikaze', 'timeout' => 0, 'cost' => 1, 'range' => 0, 'charge' => 1],
				['use' => static::spellDamage(12, ['burned' => 0.1]), 'area' => static::areaStar(2)]);

			/* ------------------------  MISC  ------------------------ */
			
			static::register(['id' => 500, 'name' => 'glow', 'timeout' => 0, 'cost' => 2, 'range' => 4]);

			/* ------------------------  ENEMIES  ------------------------ */
			
		}
		
	}	

?>
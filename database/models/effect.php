<?php

	class Effect extends BaseModel {
   		
		protected $table = 'effect';

		public function apply(Target $target) {
			global $capsule;
			
			if(is_a($target, 'Participant')) {

				$effect = $target->effects->where('id', $this->id)->first();

				if($effect) {

					$countdown = $effect->pivot->countdown;
					if($countdown == 0) {
						$target->removeEffect($this);
						return true;
					}
				
					$capsule::table('participant_effects')
						->where('participant_id', $target->id)
						->where('effect_id', $this->id)
						->decrement('countdown');

				}

			}

			return parent::apply($target);

		}
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => 'poison', 'fade_min' => 5, 'fade_max' => 7, 'block' => false], [
				'apply' => function(Target $target) {
					$damage = 4;
					return $target->damage(new DamageEvent($damage));
				}]);
			
			static::register(['id' => 2, 'name' => 'stunned', 'fade_min' => 1, 'fade_max' => 2, 'block' => true]);
			
			static::register(['id' => 3, 'name' => 'burned', 'fade_min' => 2, 'fade_max' => 4, 'block' => false], [
				'apply' => function(Target $target) {
					$damage = 2;
					return $target->damage($damage);
				}]);
			
			static::register(['id' => 4, 'name' => 'frozen', 'fade_min' => 3, 'fade_max' => 4, 'block' => true]);
			
			static::register(['id' => 5, 'name' => 'rage', 'fade_min' => 2, 'fade_max' => 4, 'block' => false], [
				'apply' => function(Target $target) {
				
				}]);
			
			static::register(['id' => 6, 'name' => 'asleep', 'fade_min' => 2, 'fade_max' => 4, 'block' => true], [
				'onDamage' => function(DamageEvent $damage, Target $target) {
					
					$target->removeEffect(Effect::find(6));

				}]);
			
		}
		
	}	

?>
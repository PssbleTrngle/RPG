<?php

	class Participant extends BaseModel implements Target {

		protected $table = 'participant';
		protected $with = ['battle', 'character', 'enemy', 'effects', 'skills', 'charging'];

		public function delete() {
			global $capsule;

			if($this->enemy)
				$this->enemy->delete();

			$capsule->table('participant_effects')->where('participant_id', $this->id)->delete();
			$capsule->table('participant_skills')->where('participant_id', $this->id)->delete();
			$capsule->table('charging_skills')->where('participant_id', $this->id)->delete();

			parent::delete();

		}
		
		public function charging() {	
			return $this->belongsToMany(Skill::class, 'charging_skills', 'participant_id', 'skill_id')
    			->withPivot('countdown', 'target_id');
		}
		
		public function skills() {	
			return $this->belongsToMany(Skill::class, 'participant_skills', 'participant_id', 'skill_id')
                ->as('usage')
    			->withPivot('nextUse');
		}

		public function battle() {
			return $this->belongsTo(Battle::class, 'battle_id');
		}
		
		public function character() {
			return $this->hasOne(Character::class, 'participant_id')->without(['participant']);
		}
		
		public function enemy() {
			return $this->hasOne(Enemy::class, 'participant_id')->without(['participant']);
		}
		
		public function effects() {
			return $this->belongsToMany(Effect::class, 'participant_effects', 'participant_id', 'effect_id')->withPivot('countdown');
		}
		
		public function useableSkills() {
			return $this->skills->where('usage.nextUse', '<=', 0);
		}

		public function addEffect($effect) {
			global $capsule;

			if($this->effects->count() < option('max_effects')) {

				if(!$this->effects->contains('id', $effect->id)) {

					$rand = rand($effect->fade_min, $effect->fade_max);

					$capsule->table('participant_effects')
						->insert(['participant_id' => $this->id, 'effect_id' => $effect->id, 'countdown' => $rand]);

					$this->refresh();
					return true;
				}

			}

			return false;
		}

		public function removeEffect($effect) {
			global $capsule;

			if($this->effects->contains('id', $effect->id)) {

				$capsule->table('participant_effects')
					->where('participant_id', $this->id)
					->where('effect_id', $effect->id)
					->delete();

				$this->refresh();
				return true;
			}

			return false;
		}
		
		public function canTakeTurn() {
			if($this->health <= 0) return false;

			if(!$this->charging->isEmpty()) return false;

			foreach ($this->effects as $effect)
				if($effect->block) {
					if($this->battle)
						$this->battle->addMessage(new Translation('blocked.'.$effect->name, $this->name()));
					return false;
				}

			return true;
		}

		public function parent() {
			return $this->character ?? $this->enemy;
		}
		
		public function key() {
			return $this->parent()->key();
		}
		
		public function icon() {
			return $this->parent()->icon();
		}

		public function health() {
			$this->health = max(0, min($this->health, $this->maxHealth()));
			$this->save();
			return $this->health;
		}
		
		public function heal($amount) {
			$this->health = min($this->maxHealth(), $this->health + abs($amount));
			$this->save();
			return true;
		}
		
		public function damage(DamageEvent $event) {
			if($this->health <= 0) return false;

			foreach ($this->effects as $effect)
				$effect->onDamage($event, $this);

			$this->health = max(0, $this->health - abs($event->amount));

			if($this->health == 0)
				$this->died = true;

			$this->save();

			return true;
		}
	
		public function validate() {

			if(!$this->parent()) {
				$this->delete();
				return false;
			}

			if($this->health <= 0) {
				$this->revive();
				return false;
			}

			return true;

		}

		public function afterTurn() {
			global $capsule;

			foreach($this->effects as $effect)
				$effect->apply($this);

			foreach ($this->charging as $skill)
				if($skill->pivot->countdown == 0) {
					$target = Participant::find($skill->pivot->target_id);
					if($target)
						$this->battle->addMessage($skill->use($target, $this, true));
				}

			$capsule->table('charging_skills')->where('participant_id', $this->id)->where('countdown', 0)->delete();
			$capsule->table('charging_skills')->where('participant_id', $this->id)->where('countdown', '>', 0)->decrement('countdown');

			$this->save();
			$this->refresh();

		}

		public function revive(Participant $by = null) {

			$amount = 0.3; 
			if(!is_null($by)) $amount = $by->stats()->apply(0.3, 'wisdom');

			$this->health = ceil($this->maxHealth() * $amount);
			$this->save();

		}

		public function __call($method, $args) {

			$parent = $this->parent();
			if(!method_exists($this, $method) && method_exists($parent, $method))
				return call_user_func_array([$parent, $method], $args);

			return parent::__call($method, $args);
		}

	}

?>
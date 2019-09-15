<?php

	class Participant extends BaseModel implements Target {

		protected $table = 'participant';
		protected $with = ['battle', 'character', 'enemy', 'effects'];

		public function delete() {
			global $capsule;

			if($this->enemy)
				$this->enemy->delete();

			$capsule->table('participant_effects')->where('participant_id', $this->id)->delete();

			parent::delete();

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
			return $this->health > 0;
		}

		public function parent() {
			return $this->character ?? $this->enemy;
		}
		
		public function maxHealth() {
			return $this->parent()->maxHealth();
		}
		
		public function name() {
			return $this->parent()->name();
		}
		
		public function level() {
			return $this->parent()->level();
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
		
		public function damage($amount, $source = null) {
			if($this->health <= 0) return false;

			$this->health = max(0, $this->health - abs($amount));

			if($this->health == 0)
				$this->died = true;

			$this->save();

			return true;
		}
	
		public function validate() {

			if(($this->enemy_id && !$this->battle_id) || !$this->parent()) {
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

			foreach($this->effects as $effect)
				$effect->apply($this);

			$this->save();
			$this->refresh();

		}

		public function revive(Participant $by = null) {

			$amount = 0.3; 
			if(!is_null($by)) $amount = $by->parent()->stats()->apply(0.3, 'wisdom');

			$this->health = ceil($this->maxHealth() * $amount);
			$this->save();

		}

	}

?>
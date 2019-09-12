<?php

	class Participant extends BaseModel {

		protected $table = 'participant';
		protected $with = ['battle', 'character', 'enemy', 'effects'];

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
			return $this->belongsToMany(Effect::class, 'participant_effects', 'participant_id', 'effect_id');
		}

		public function addEffect($effect) {
			global $capsule;

			$capule::table('participant_effects')->insert(['participant_id' => $this->id, 'effect_id' => $effect->id]);
			$this->refresh();			
			
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
		
	}	

?>
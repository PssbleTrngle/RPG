<?php

	class Battle extends BaseModel {
   		
		protected $table = 'battle';
		protected $with = ['enemies', 'characters', 'active', 'position'];
		
		public function active() {
			return $this->belongsTo(Character::class, 'active_id')->without(['clazz', 'race', 'position', 'battle', 'canEvolveTo', 'inventory', 'account']);
		}
		
		public function position() {
			return $this->belongsTo(Position::class, 'position_id');
		}
		
		public function enemies() {
			return $this->hasMany(Enemy::class, 'battle_id')->without(['battle', 'account']);
		}
		
		public function characters() {
			return $this->hasMany(Character::class, 'battle_id')->without(['battle', 'account']);
		}
		
		private function end() {
			global $capsule;
			
			foreach($this->characters as $character) {
				$character->battle_id = null;
				$character->save();
			
				$capsule::table('character_skills')
					->where('character_id', $character->id)
					->update(['nextUse' => 0]);
			}
			
			foreach($this->enemies as $enemy) {
				$enemy->delete();
			}
			
			$this->delete();
			
		}
		
		public function win() {
			
			foreach($this->characters as $character) {
				$character->addLoot($this->getLoot());
			}
			
			$this->refresh();
			$this->end();
			
		}
		
		public function loose() {
			
			$this->end();
			
		}
		
		public function run($character) {
			
			if($this->active_id == $character->id) {
				
				$character->battle_id = null;
				$character->save();

				$this->refresh();

				if($this->characters->count > 0 && $this->active_id == $character->id) $this->next($character->name.' ran away');
				$this->save();
				$this->refresh();
			
			}
			
		}
		
		public function participants() {
		
			return $this->characters->toBase()->merge($this->enemies);
			
		}
		
		private function activeIndex() {
			
			for($index = 0; $index < $this->participants()->count(); $index++) {
				$participant = $this->participants()[$index];
				if(is_a($participant, 'Character') && $participant->id == $this->active_id)
					return $index;

			}
			
		}
		
		public function next($charMessage = "") {
		
			$this->message = $charMessage.'\n';
			$this->save();
			$count = $this->participants()->count();
			
			if($this->characters->count() == 0) {
				$this->end();
				return true;
			}
			
			$index = ($this->activeIndex() + 1);
			while(!is_a($next = $this->participants()[$index], 'Character')) {
				
				if(is_a($next, 'Enemy') && $next->canTakeTurn()) $this->message .= $next->takeTurn($this).'\n';
				$this->save();
				$this->refresh();
				
				$index = ($index + 1) % $count;
				if($index == 0) $this->nextRound();
			}
				
			$this->active_id = $next->id;
			$this->save();
			$this->refresh();
			
			return $this->message;
			
		}
			
		private function nextRound() {
			global $capsule;
			
			foreach($this->characters as $character) {
			
				$capsule::table('character_skills')
					->where('character_id', $character->id)
					->where('nextUse', '>', 0)
					->decrement('nextUse');
				
			}
			
			$this->round++;
			$this->save();
			
		}
		
		public function valid() {
		
			if($this->characters()->count() == 0) {	
				$this->end();
				return false;
			}
	
			return true;
			
		}
		
		public static function start($character) {
			if(!$character) return false;
			
			if($character) {
				$battle = new Battle;
				$battle->active_id = $character->id;
				$battle->position_id = $character->id;
				$battle->message = '';
				$battle->save();
				$battle->refresh();
				$battle->addCharacter($character);
				return $battle;
			}
			
			return false;
		}
		
		public function addCharacter($character) {
			if(!$character) return false;
			
			if($character) {
				$character->battle_id = $this->id;
				$character->save();
				$character->refresh();
			}
		}
		
		public function addNPC($npc) {
			if(!$npc) return false;
			
			if($npc && $enemy = $npc->createEnemy()) {
				
				$suffix = 'A';
				foreach($this->enemies as $other) if($other->npc->id == $npc->id)
					if($other->suffix && ord($other->suffix) >= ord($suffix)) 
						$suffix = chr(ord($other->suffix) + 1);
				
				$enemy->battle_id = $this->id;
				$enemy->suffix = $suffix;
				
				$enemy->save();
			}
		}
		
		public function getLoot() {
		
			$loot = [];
			$loot['xp'] = 0;
			$loot['items'] = [];
			foreach($this->enemies as $enemy) {
			
				$npc = $enemy->npc;
				$loot['xp'] += pow(1.5, $npc->level - 1);
				
				foreach($npc->loot as $item) {
					$inventory = new Stack;
					$inventory->item_id = $item->id;
					$inventory->amount = 1;
					$loot['items'][] = $inventory;
				}
				
			}
			
			return $loot;
			
		}
		
	}

	class Participant extends BaseModel {

		protected $hidden = ['health'];
		
		public function name() {
			return $this->name;
		}
		
		public function battle() {
			return $this->belongsTo(Battle::class, 'battle_id');
		}
		
		public function canTakeTurn() {
			return $this->health > 0;
		}
		
		public function health() {
			$this->health = max(0, min($this->health, $this->maxHealth()));
			$this->save();
			return $this->health;
		}
		
		public function heal($amount) {
			$this->health = min($this->maxHealth(), $this->health + $amount);
			$this->save();
			return true;
		}
		
		public function damage($amount, $source = null) {
			$this->health = max(0, $this->health - $amount);
			$this->save();
			return true;
		}
		
	}		

?>
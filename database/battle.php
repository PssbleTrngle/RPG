<?php

	class Battle extends BaseModel {
   		
		protected $table = 'battle';
		protected $with = ['enemies', 'characters', 'active', 'position'];
		
		public function active() {
			return $this->belongsTo(Character::class, 'active')->without(['clazz', 'race', 'position', 'battle', 'canEvolveTo', 'inventory', 'account']);
		}
		
		public function position() {
			return $this->belongsTo(Position::class, 'position');
		}
		
		public function enemies() {
			return $this->hasMany(Enemy::class, 'battle')->without(['battle', 'account']);
		}
		
		public function characters() {
			return $this->hasMany(Character::class, 'battle')->without(['battle', 'account']);
		}
		
		private function end() {
			global $capsule;
			
			foreach($this->relations['characters'] as $character) {
				$character->battle = null;
				$character->save();
			
				$capsule::table('character_skills')
					->where('character', $character->id)
					->update(['nextUse' => 0]);
			}
			
			foreach($this->relations['enemies'] as $enemy) {
				$enemy->delete();
			}
			
			$this->delete();
			
		}
		
		public function win() {
			
			foreach($this->relations['characters'] as $character) {
				$character->addLoot($this->getLoot());
			}
			
			$this->refresh();
			$this->end();
			
		}
		
		public function loose() {
			
			$this->end();
			
		}
		
		public function run($character) {
			
			if($this->active == $character->id) {
				
				$character->battle = null;
				$character->save();

				$this->refresh();

				if($this->relations['characters']->count > 0 && $this->active == $character->id) $this->next($character->name.' ran away');
				$this->save();
				$this->refresh();
			
			}
			
		}
		
		public function participants() {
		
			return $this->relations['characters']->toBase()->merge($this->relations['enemies']);
			
		}
		
		private function activeIndex() {
			
			for($index = 0; $index < $this->participants()->count(); $index++) {
				$participant = $this->participants()[$index];
				if(is_a($participant, 'Character') && $participant->id == $this->active)
					return $index;

			}
			
		}
		
		public function next($charMessage = "") {
		
			$this->message = $charMessage.'\n';
			$this->save();
			$count = $this->participants()->count();
			
			if($this->relations['characters']->count() == 0) {
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
				
			$this->active = $next->id;
			$this->save();
			$this->refresh();
			
			return $this->message;
			
		}
			
		private function nextRound() {
			global $capsule;
			
			foreach($this->relations['characters'] as $character) {
			
				$capsule::table('character_skills')
					->where('character', $character->id)
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
		
		public function start($character) {
			if(is_numeric($character)) $character = Character::find($character);	
			
			if($character) {
				$battle = new Battle;
				$battle->active = $character->id;
				$battle->position = $character->id;
				$battle->save();
				$battle->refresh();
				$battle->addCharacter($character);
				return $battle;
			}
			
			return false;
		}
		
		public function addCharacter($character) {
			if(is_numeric($character)) $character = Character::find($character);	
			
			if($character) {
				$character->battle = $this->id;
				$character->save();
				$character->refresh();
			}
		}
		
		public function addNPC($npc) {
			if(is_numeric($npc)) $npc = NPC::find($npc);	
			
			if($npc && $enemy = $npc->createEnemy()) {
				
				$suffix = 'A';
				foreach($this->relations['enemies'] as $other) if($other->npc == $npc->id) {
				
					if($other->suffix && ord($other->suffix) >= ord($suffix)) $suffix = chr(ord($other->suffix) + 1);
					
				}
				
				$enemy->battle = $this->id;
				$enemy->suffix = $suffix;
				
				$enemy->save();
			}
		}
		
		public function getLoot() {
		
			$loot = [];
			$loot['xp'] = 0;
			$loot['items'] = [];
			foreach($this->relations['enemies'] as $enemy) {
			
				$npc = $enemy->relations['npc'];
				$loot['xp'] += pow(1.5, $npc->level - 1);
				
				foreach($npc->relations['loot'] as $item) {
					$inventory = new Stack;
					$inventory->item = $item->id;
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
			return $this->belongsTo(Battle::class, 'battle');
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

	class Stats extends BaseModel {
   		
		protected $table = 'stats';
		public $keys = array('wisdom', 'strength', 'resistance', 'agility', 'luck');
		
		public function total() {
			
			$total = 0;
			foreach($this->keys as $stat)
				$total += $this->$stat;
			
			return $total;
		}
		
		public function save(array $options = []) {}
  
		public function add($other) {
			$stats = new Stats;			
			foreach($this->keys as $stat)
				$stats->$stat = $this->$stat + $other->$stat;
			
			return $stats;
			
		}

		public function statFactor($name) {

			return 0.2;
			return $this->$name / 100;

		}

		public function apply($value, $stat, $negative = false) {

			if(negative)
				$by = 1 + $this->statFactor($stat);
			else
				$by = 1 - $this->statFactor($stat);


			return floor($value * $by);

		}
		
	}
		
		

?>
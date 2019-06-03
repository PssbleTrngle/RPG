<?php

	class Battle extends BaseModel {
   		
		protected $table = 'battle';
		protected $with = ['enemies', 'characters', 'active'];
		
		public function active() {
			return $this->belongsTo(Character::class, 'active')->without(['clazz', 'race', 'position', 'battle', 'canEvolveTo', 'inventory', 'account']);
		}
		
		public function enemies() {
			return $this->hasMany(Enemy::class, 'battle')->without(['battle', 'account']);
		}
		
		public function characters() {
			return $this->hasMany(Character::class, 'battle')->without(['battle', 'account']);
		}
		
		public function end() {
			
			foreach($this->relations['characters'] as $character) {
				$character->battle = null;
				$character->save();
			}
			
			foreach($this->enemies() as $enemy) {
				$enemy->delete();
			}
			
			$this->delete();
			
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
			
			$count = $this->participants()->count();
			
			$index = ($this->activeIndex() + 1);
			while(!is_a($next = $this->participants()[$index], 'Character')) {
				
				if(is_a($next, 'Enemy') && $next->canTakeTurn()) $this->message .= $next->takeTurn().'\n';
				
				$index = ($index + 1) % $count;
				if($index == 0) $this->nextRound();
			}
				
			$this->active = $next->id;
			$this->save();
			
			return $this->message;
			
		}
			
		private function nextRound() {
			global $capsule;
			
			foreach($this->characters() as $character) {
			
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
			}
		}
		
		public function addNPC($npc) {
			if(is_numeric($npc)) $npc = NPC::find($npc);	
			
			if($npc && $enemy = $npc->createEnemy()) {
				$enemy->battle = $this->id;
				$enemy->save();
			}
		}
		
		public function getLoot() {
		
			$loot = [];
			$loot['xp'] = 0;
			foreach($this->enemies() as $enemy) {
			
				$loot['xp'] += pow(1.5, $enemy->npc()->level - 1);
				
			}
			
			return $loot;
			
		}
		
	}

	class Participant extends BaseModel {

		protected $hidden = ['health'];
		
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
		
	}

	class Enemy extends Participant {
   		
		protected $table = 'enemy';
		protected $with = ['npc', 'battle'];
		
		public function takeTurn() {
			
			if(($battle = $this->battle()) && ($characters = $battle->characters())) {
				
				if(rand(1, 100) < 10) {
					
					return $this->relations['npc']->name.' called for help';
					
				} else {
					$target = $characters->random();
					$damage = 5;
					
					$target->damage($damage);
					return $this->relations['npc']->name.' dealt '.$damage.'K damage';
				}
				
				return $this->relations['npc']->name.' was too scared to attack';
				
			}
		
			
		}
		
		public function maxHealth() {
			return $this->relations['npc']->maxHealth;
		}
		
		public function npc() {
			return $this->belongsTo(NPC::class, 'npc');
		}
		
		public function damage($amount) {
			$this->health -= $amount;
			if($this->health <= 0) {
				$this->health = 0;
			}
				
			$this->save();
			return true;
		}
		
	}

	class NPC extends BaseModel {
   		
		protected $table = 'npc';
		
		public function createEnemy() {
			$enemy = new Enemy;
			$enemy->npc = $this->id;
			$enemy->health = $this->maxHealth;
			$enemy->save();
			$enemy->refresh();
			return $enemy;
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
		
	}
		
		

?>
<?php

	class Message extends BaseModel {

		protected $table = 'battle_messages';
		private static $glue = ';';

		public function __construct($key = '', $args = null) {
			$this->key = $key;
			if($args) $this->args = implode(static::$glue, str_replace(static::$glue, ',', $args));
		}

		public function format() {
			$args = $this->args ? explode(static::$glue, $this->args) : [];
			return format('message.battle.'.$this->key, $args);
		}

	}

	class Battle extends BaseModel {
   		
		protected $table = 'battle';
		protected $with = ['participants', 'active', 'position', 'messages'];

		public function addMessage($message) {

			if(is_string($message))
				$message = new Message($message);

			$message->battle_id = $this->id;
			$message->save();

		}
		
		public function messages() {
			return $this->hasMany(Message::class, 'battle_id');
		}

		public function active() {
			return $this->belongsTo(Character::class, 'active_id')->without(['clazz', 'race', 'position', 'battle', 'canEvolveTo', 'inventory', 'account']);
		}
		
		public function position() {
			return $this->belongsTo(Position::class, 'position_id');
		}
		
		public function participants() {
			return $this->hasMany(Participant::class, 'battle_id')->without(['battle']);
		}

		public function enemies($asParticipants = false) {
			$participants = $this->participants->where('enemy', '!=', null);
			return $asParticipants ? $participants : $participants->pluck('enemy');
		}

		public function characters($asParticipants = false) {
			$participants = $this->participants->where('character', '!=', null);
			return $asParticipants ? $participants : $participants->pluck('character');
		}
		
		public function delete() {
			global $capsule;
			
			foreach($this->participants as $participant)
				if($participant->character) {
					$participant->battle_id = null;
					$participant->save();
				
					$capsule::table('character_skills')
						->where('character_id', $participant->character->id)
						->update(['nextUse' => 0]);

				} else {
					$participant->delete();
				}

			foreach ($this->messages as $message)
				$message->delete();
			
			parent::delete();
			
		}
		
		public function win() {
			
			foreach($this->characters() as $character) {
				$character->message = 'won';
				$character->addLoot($this->getLoot());
			}
			
			$this->refresh();
			$this->delete();
			
		}
		
		public function loose() {
			
			foreach($this->characters() as $character) {
				$character->message = 'lost';
				$character->participant->revive();
				$character->save();
				/* TODO loose something */
			}
			
			$this->delete();
			
		}
		
		public function run($character) {
			
			if($this->active_id == $character->id) {
				
				$character->message = 'ran';
				$character->save();

				$character->participant->battle_id = null;
				$character->participant->save();

				$this->refresh();

				if($this->characters()->count() > 0) {
					$this->next($character->name.' ran away');

					$this->save();
					$this->refresh();

				} else {
					$this->delete();
				}

				return true;
			
			}

			return false;
			
		}
		
		private function activeIndex() {

			/* TODO rework */
			
			for($index = 0; $index < $this->participants->count(); $index++) {
				$participant = $this->participants[$index];
				if($participant->character && $participant->character->id == $this->active_id)
					return $index;

			}
			
		}

		public function prepareTurn() {

			foreach($this->participants as $participant) {
				$participant->died = false;
				$participant->save();
			}

		}
		
		public function next() {
		
			$count = $this->participants->count();

			$this->active->participant->afterTurn();
			
			$index = ($this->activeIndex() + 1) % $count;
			while(is_null(($next = $this->participants[$index])->character)) {
				
				if($next->enemy && $next->canTakeTurn()) {
					$msg = $next->enemy->takeTurn();
					if($msg) $this->addMessage($msg);
					$next->enemy->refresh();
				}

				$next->afterTurn();
				
				$index = ($index + 1) % $count;
				if($index == 0) $this->nextRound();
			}

			$this->active_id = $next->character->id;
			$this->save();
			$this->refresh();

			$this->validate();
			
			return true;
			
		}
			
		private function nextRound() {
			global $capsule;
			
			foreach($this->participants as $participant) if($participant->character) {
			
				$capsule::table('character_skills')
					->where('character_id', $participant->character->id)
					->where('nextUse', '>', 0)
					->decrement('nextUse');
				
			}
			
			$this->round++;
			
		}
		
		public static function start($character) {
			
			if($character) {
				$battle = new Battle;
				$battle->active_id = $character->id;
				$battle->position_id = $character->id;
				$battle->save();
				$battle->refresh();
				$battle->addCharacter($character);
				return $battle;
			}
			
			return false;
		}
		
		public function addCharacter($character) {			
			if($character) {
				$character->participant->battle_id = $this->id;
				$character->participant->save();
				$character->participant->refresh();
			}
		}
		
		public function addNPC($npc) {			
			$enemy = $npc->createEnemy($this);
		}
		
		public function getLoot() {
		
			$loot = [];
			$loot['xp'] = 0;
			$loot['items'] = [];
			foreach($this->enemies() as $enemy) {
			
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

		public function validate() {

			if($this->characters()->count() == 0) {
				$this->delete();
				return false;
			}

			$lost = true;
			foreach($this->characters(true) as $participant)
				$lost &= $participant->health() <= 0;
			
			if($lost) {
				$this->loose();
				return false;	
			}

			$won = true;
			foreach($this->enemies(true) as $participant)
				$won &= $participant->health() <= 0;

			if($won) {
				$this->win();
				return false;
			}

			return true;

		}
		
	}	

?>
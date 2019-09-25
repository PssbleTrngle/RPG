<?php

	class Translation extends BaseModel {

		protected $table = 'battle_messages';
		private static $glue = ';';

		public function __construct($key = '', $args = null) {
			$this->key = $key;
			if(is_string($args)) $args = [$args];
			if($args) $this->args = implode(static::$glue, str_replace(static::$glue, ',', $args));
		}

		public function format() {
			$args = $this->args ? explode(static::$glue, $this->args) : [];
			return format($this->key, $args);
		}

	}

	class Battle extends BaseModel {
   		
		protected $table = 'battle';
		protected $with = ['participants', 'active', 'position', 'messages'];
		public $sides = [1, 2];

		public function addMessage($message) {

			if(is_string($message))
				$message = new Translation($message);

			$message->key = 'message.battle.'.$message->key;
			$message->battle_id = $this->id;
			$message->save();

		}
		
		public function messages() {
			return $this->hasMany(Translation::class, 'battle_id')->orderBy('id', 'desc');
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

		public function onSide($side, $living = false) {
			$all = $this->participants->where('side', $side);
			if($living) $all = $all->where('health', '>', 0);
			return $all;
		}
		
		public function delete() {
			global $capsule;
			
			foreach($this->participants as $participant)
				if($participant->character) {
					$participant->battle_id = null;
					$participant->save();
				
					$capsule::table('participant_skills')
						->where('participant_id', $participant->id)
						->update(['nextUse' => 0]);

				} else {
					$participant->delete();
				}

			foreach ($this->messages as $message)
				$message->delete();
			
			parent::delete();
			
		}
		
		public function win($side) {
			
			$loot = $this->getLoot();
			foreach($this->onSide($side) as $participant) {
				$participant->addLoot($loot);
				$participant->win();
			}
			
			$this->delete();
			
		}
		
		public function loose($side) {
			
			foreach($this->onSide($side) as $participant)
				$participant->loose();
			
		}
		
		public function run($character) {
			
			if($this->active_id == $character->id) {
				
				$character->message = 'ran';
				$character->save();

				$character->participant->battle_id = null;
				$character->participant->save();

				$this->refresh();

				$this->addMessage($character->name.' ran away');

				if($this->validate())
					$this->next();
				else
					$this->delete();

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
			
			foreach($this->participants as $participant) {
			
				$capsule::table('participant_skills')
					->where('participant_id', $participant->id)
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
				$character->message = null;
				$character->participant->battle_id = $this->id;
				$character->participant->side = 1;
				$character->participant->save();
				$character->participant->refresh();
				$character->save();
			}
		}
		
		public function addNPC($npc) {			
			$npc->createEnemy($this, 2);
		}
		
		public function getLoot() {
		
			$loot = new Loot([]);

			foreach($this->participants as $participant) {
				$loot->add($participant->getLoot());
			}
			
			return $loot;
			
		}

		public function validate() {

			$remainingSides = collect([]);

			foreach($this->sides as $side) {

				$living = $this->onSide($side)->filter(function($p, $k) {
					return $p->validate() && $p->health() > 0;
				});

				if($living->isEmpty())
					$this->loose($side);
				else 
					$remainingSides[] = $side;

			}

			/* Nobody left in the battle */
			if($remainingSides->count() == 0) {
				$this->delete();
				return false;
			}

			/* One side left battle */
			if($remainingSides->count() == 1) {
				$this->win($remainingSides->first());
				return false;
			}

			return true;

		}
		
	}	

?>
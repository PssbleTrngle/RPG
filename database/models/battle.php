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
		protected $with = ['participants', 'active', 'position', 'messages', 'fields'];
		public $sides = [1, 2];

		public function addMessage($message) {
			if($message) {
				if(is_string($message))
					$message = new Translation($message);

				$message->key = 'message.battle.'.$message->key;
				$message->battle_id = $this->id;
				$message->save();
			}
		}
		
		public function fields() {
			return $this->hasMany(Field::class, 'battle_id')->without('battle');
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
			
			foreach($this->participants as $participant) {
				
				$capsule::table('participant_skills')
					->where('participant_id', $participant->id)
					->update(['nextUse' => 0]);
				
				$capsule::table('charging_skills')
					->where('participant_id', $participant->id)
					->delete();

				if($participant->character) {

					$participant->battle_id = null;
					$participant->save();

				} else {
					$participant->delete();
				}
			}

			foreach ($this->messages as $message)
				$message->delete();

			foreach ($this->fields as $field)
				$field->delete();
			
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

			/* Apply effects */
			$this->active->participant->afterTurn();
		
			$count = $this->participants->count();
			
			$index = ($this->activeIndex() + 1) % $count;
			while(is_null(($next = $this->participants[$index])->character)) {
				
				/* Take their turn */
				if($next->canTakeTurn()) {
					$this->addMessage($next->takeTurn());
					$next->refresh();
				}

				/* Apply effects */
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

		public function createHex($radius, $centerX = 0, $centerY = 0) {

			for($x = -$radius; $x <= $radius; $x++)
				for($y = -$radius; $y <= $radius; $y++)
					if(abs($x + $y) <= $radius) {

						if(!$this->fieldAt($centerX + $x, $centerY + $y)) {

							$field = new Field;
							$field->x = $centerX + $x;
							$field->y = $centerY + $y;
							$field->battle_id = $this->id;
							$field->save();
						
						}
					}

			$this->refresh();

		}
		
		public static function start($character) {
			
			if($character) {

				$battle = new Battle;
				$battle->active_id = $character->id;
				$battle->position_id = $character->id;
				$battle->save();
				$battle->refresh();

				$battle->createHex(2, -2);
				$battle->createHex(2, 2);

				$battle->addCharacter($character);

				return $battle;
			}
			
			return false;
		}

		public function fieldAt($x, $y) {
			return $this->fields->where('x', $x)->where('y', $y)->first();
		}
		
		public function addCharacter($character) {			
			if($character) {

				$field = $this->fieldAt(0, 0);
				if($field) {
					$field->participant_id = $character->participant->id;
					$field->save();
				}

				$character->message = null;
				$character->participant->battle_id = $this->id;
				$character->participant->side = 1;
				$character->participant->save();
				$character->save();
			}
		}
		
		public function addNPC($npc) {			
			$enemy = $npc->createEnemy($this, 2);

			if($enemy) {

				$field = $this->fieldAt(1, 0);
				if($field) {
					$field->participant_id = $enemy->participant->id;
					$field->save();
				}

			}
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

	class Battlefield {

		public $fields;
		public $active;

		public function __construct() {

		}

	}

	class Field extends BaseModel {
   		
		protected $table = 'field';
		protected $with = ['participant', 'battle'];
		
		public function participant() {
			return $this->belongsTo(Participant::class, 'participant_id')->without('battle', 'field');
		}
		
		public function battle() {
			return $this->belongsTo(Battle::class, 'battle_id');
		}

	}

?>
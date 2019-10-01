<?php

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

		public function createHex($radius, $centerX = 0, $centerY = 0, $side = null) {

			for($x = -$radius; $x <= $radius; $x++)
				for($y = -$radius; $y <= $radius; $y++)
					if(abs($x + $y) <= $radius) {

						$field = $this->fieldAt($centerX + $x, $centerY + $y) ?? new Field;

						if($side && $x == 0 && $y == 0) $field->spawn = $side;

						$field->x = $centerX + $x;
						$field->y = $centerY + $y;
						$field->battle_id = $this->id;
						$field->save();
					
					}

			$this->refresh();

		}

		public function possibleSpawns($side) {

			if($side) {
				$spawn = $this->fields->where('spawn', $side);
				if(!$spawn->isEmpty()) {

					$spawn = $spawn->random();
					$hex = Skill::areaHexagon()();
					$fields = $this->fieldsIn($hex, $spawn->x, $spawn->y)->where('participant', NULL);
					if(!$fields->isEmpty())
						return $fields;

				}

				return null;

			}

			return null;
			$all = $this->fields->where('participant', NULL);
			if($all->isEmpty()) return null;
			return $all;	

		}
		
		public static function start($character) {
			
			if($character) {

				$battle = new Battle;
				$battle->active_id = $character->id;
				$battle->position_id = $character->id;
				$battle->save();
				$battle->refresh();

				$battle->createHex(2, -1, 0, $battle->sides[0]);
				$battle->createHex(2, 1, 0, $battle->sides[1]);

				$battle->addCharacter($character);

				return $battle;
			}
			
			return false;
		}

		public function fieldAt(int $x, int $y) {
			return $this->fields->where('x', $x)->where('y', $y)->first();
		}

		public function fieldsIn($range, int $x, int $y) {
			if(is_array($range)) $range = collect($range);

			$range->each(function($value, $key) use($range, $x, $y) {

				$value['x'] += $x;
				$value['y'] += $y;
				$range[$key] = $value;

			});

			$fields = [];
			foreach($range as $pos)
				$fields[] = $this->fieldAt($pos['x'], $pos['y']);

			return collect($fields)->filter();

			return $this->fields->filter(function($field) use($range) {
				return $range->contains($field->pos());
			});

		}
		
		public function addCharacter($character, $side = 1) {	

			$spawns = $this->possibleSpawns($side);
			if($spawns) $field = $spawns->random();

			if($field && !$field->participant) {
				if($character) {

					$character->message = null;
					$character->participant->battle_id = $this->id;
					$character->participant->side = 1;
					$character->participant->save();
					$field->participant_id = $character->participant->id;
					$field->save();
					
					$character->save();
				}

			}
		}
		
		public function addNPC($npc, $field = null, $side = 2) {

			if(is_null($field)) {
				$spawns = $this->possibleSpawns($side);
				if($spawns) $field = $spawns->random();
			}

			if($field && !$field->participant) {

				$enemy = $npc->createEnemy($this, $side);

				if($enemy) {
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

			/* One side left battle */
			if($this->fields->isEmpty()) {
				$this->delete();
				return false;
			}

			return true;

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

		public function validate() {

			if(!$this->battle) {
				$this->delete();
				return false;
			}

			return true;

		}

		public function pos() {
			return ['x' => $this->x, 'y' => $this->y];
		}

	}

?>
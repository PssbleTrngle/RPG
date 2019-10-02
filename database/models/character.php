<?php

	class Character extends ParticipantModel {
   		
		protected $table = 'character';
		protected $with = ['classes', 'race', 'position', 'inventory', 'account'];
		
		public function loose() {

			$this->message = 'lost';
			$this->participant->revive();
			$this->save();
			
		}
		
		public function win() {

			$character->message = 'won';
			$this->participant->revive();
			$this->save();
			
		}
		
		public function icon() {
			if($this->classes->isEmpty()) return null;
			return $this->classes->last()->icon();
		}
		
		public function race() {
			return $this->belongsTo(Race::class, 'race_id');
		}
		
		public function addLoot(Loot $loot) {
			
			$this->addXp($loot->xp);
			
			foreach($loot->items as $item) {
				
				$item->character_id = $this->id;
				$item->slot_id = Slot::where('name', 'loot')->first()->id;
				$item->save();
				
			}
			
		}

		private function levelUp() {

			$this->message = 'level_up';
			$this->level++;
			$this->skillpoints += ceil($this->level / 5) + 1;

			$this->save();

		}

		public function addXp($xp) {

			$this->xp += $xp;
			$this->validate();
			$this->save();

		}

		public function learn($skill) {
			global $capsule;

			if(!$this->participant->skills->contains('id', $skill->id)) {
				if($skill && $this->canLearn()->contains('id', $skill->id)) {

					if($this->skillpoints >= $skill->cost) {
				
						$capsule::table('participant_skills')
							->insert(['skill_id' => $skill->id, 'participant_id' => $this->participant->id]);

						$this->skillpoints--;
						$this->save();

						return true;

					}

				}
			}

			return false;
		}
		
		public function bagSize() {
			return option('base_bag_size');
		}

		public function classes() {
			return $this->belongsToMany(Clazz::class, 'character_classes', 'character_id', 'class_id');
		}
		
		public function account() {
			return $this->belongsTo(Account::class, 'account_id')->without(['characters']);
		}
		
		public function inventory() {
			return $this->hasMany(Stack::class, 'character_id')->without(['character']);
		}
		
		public function stats() {
			$stats = $this->race->stats;

			foreach ($this->classes as $class)
				$stats = $stats->add($class->stats);

			foreach ($this->inventory as $stack) 
				if($stack->slot->apply_stats)
					$stats = $stats->add($stack->item->stats);

			return $stats;
		}
		
		public function position() {
			return $this->belongsTo(Position::class, 'id');
		}
		
		public function itemIn($slot) {
			if(is_string($slot)) $slot = Slot::where('name', $slot)->first();

			if($slot) {
				$items = $this->inventory->where('slot_id', '=', $slot->id);
				if($slot->space == 1) return $items->first();
				return $items;
			}
			
			return false;			
		}
		
		public function isSelected() {
			$selected = $this->account()->first()->selected;
			return $selected && $selected->id == $this->id;
		}

		public function canTravel() {
			return !$this->participant->battle && !$this->position->dungeon;
		}
		
		public function travel($location) {

			if($location->id == $this->position->location->id)
				return ['success' => false, 'message' => 'Already here'];
			
			if($location->area->level <= $this->level()) {
				
				if($this->canTravel()) {
				
					$this->position->location_id = $location->id;
					$this->position->save();
				
					return true;
					
				}

				return ['success' => false, 'message' => 'You are not able to travel'];
						
			}

			return ['success' => false, 'message' => 'Youre level is not high enough'];
		}
		
		public function enter($dungeon) {
			
			if($dungeon->level <= $this->level()) {
				
				if($this->canTravel()) {
				
					$this->position->dungeon_id = $dungeon->id;
					$this->position->foundStairs = false;
					$this->position->floor = 1;
					$this->position->attempts = 0;
					$this->position->save();
				
					return true;		
					
				}

				return ['success' => false, 'message' => 'You are not able to travel'];

			}

			return ['success' => false, 'message' => 'Youre level is not high enough'];
		}
		
		public function leave() {
			
			if($this && $this->position->dungeon && !$this->participant->battle) {
			
				$this->position->dungeon_id = null;
				$this->position->floor = null;
				$this->position->attempts = 0;
				$this->position->foundStairs = false;
				$this->position->save();
				return true;
				
			}
			
			return false;
			
		}
		
		public function level() {
			return $this->level;
		}
		
		public function requiredXp($level = null) {
			if(is_null($level)) $level = $this->level + 1;		
			return 10 * ($level - 1);
		}
		
		public function evolve($to) {
			global $capsule;

			if($to) {
				if($this->canEvolveTo()->contains('id', $to->id)) {
					$capsule->table('character_classes')->insert(['character_id' => $this->id, 'class_id' => $to->id]);
					return true;
				}
			}

			return false;
			
		}
		
		public function canEvolveTo() {
			/* TODO Remove Unneccessary Query */
			if($this->classes->isEmpty()) return Clazz::where('id', '<', 10)->get();
			return $this->classes->last()->evolvesTo->where('evolution.level', '<=', $this->level());
		}
		
		public function canLearn() {
			$skills = collect([]);

			foreach($this->classes as $class) {
				$skills = $skills->merge(
					$class->skills()
						->wherePivot('level', '<=', $this->level())
						->get()
						->filter(function($value, $key) {
							return !$this->participant->skills->contains('id', $value->id);
						})->all());
			}		
			return $skills;
		}
		
		public function maxHealth() {
			return 100;	
		}

		public function description() {
			return format('character', [$this->race->name(), $this->classes->last()->name()]);
		}

		public function name() {
			return $this->name;
		}

		public function createPosition() {

			$position = new Position;
			$position->id = $this->id;
			$position->save();

		}

		public function createParticipant() {

			$participant = new Participant;
			$participant->health = $this->maxHealth();
			$participant->save();
			$participant->refresh();
			$this->participant_id = $participant->id;
			$this->save();

		}

		public function validate() {
			global $capsule;
			$correct = true;

			if(!$this->position) {
				$this->createPosition();
				$correct = false;
			}

			if($this->classes->isEmpty()) {
				$this->evolve(Clazz::where('id', '<', 10)->get()->random());
				$correct = false;
			}
			
			if(!$this->participant) {
				$this->createParticipant();
				$correct = false;
			}

			if($this->classes->isEmpty()) {
				$this->evolve(Clazz::find(1));
				$correct = false;
			}

			while($this->xp >= $this->requiredXp()) {

				$this->xp -= $this->requiredXp();
				$this->levelUp();
				$correct = false;

			}

			if($this->account->hasPermission('tester'))
				foreach ($this->canLearn() as $skill)
					$this->learn($skill);

			return $correct;
		}
		
	}
		

?>
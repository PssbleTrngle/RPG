<?php

	class Character extends BaseModel {
   		
		protected $table = 'character';
		protected $with = ['clazz', 'race', 'position', 'inventory', 'account'];
		
		public function icon() {
			return $this->clazz->icon();
		}
		
		public function race() {
			return $this->belongsTo(Race::class, 'race_id');
		}
		
		public function addLoot($loot) {
			
			if(array_key_exists('xp', $loot))
				$this->addXp($loot['xp']);
			
			foreach($loot['items'] as $item) {
				
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

			if($skill && $this->canLearn()->contains('id', $skill->id)) {

				if($this->skillpoints >= $skill->cost) {
			
					$capsule::table('character_skills')
						->insert(['skill_id' => $skill->id, 'character_id' => $this->id]);

					$this->skillpoints--;
					$this->save();

					return true;

				}

			}

			return false;
		}
		
		public function bagSize() {
			return option('base_bag_size');
		}
		
		public function clazz() {
			return $this->belongsTo(Clazz::class, 'class_id');
		}
		
		public function account() {
			return $this->belongsTo(Account::class, 'account_id')->without(['characters']);
		}
		
		public function inventory() {
			return $this->hasMany(Stack::class, 'character_id')->without(['character']);
		}
		
		public function stats() {
			return $this->clazz->stats->add($this->race->stats);
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
			
			if($location->level <= $this->level()) {
				
				if($this->canTravel()) {
				
					$this->position->location_id = $location->id;
					$this->position->save();
				
					return true;
					
				}				
			}
			
			return false;
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
				$this->position->floor = 1;
				$this->position->attempts = 0;
				$this->position->foundStairs = 0;
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
			if(!$to) return false;
			
			if($to) {
				foreach($this->canEvolveTo() as $can)
					if($can->id == $to->id) {
						$this->class = $to->id;
						$this->save();
						return true;
					}
			}

			return false;
			
		}
		
		public function canEvolveTo() {
			/* TODO Remove Unneccessary Query */
			return $this->clazz->evolvesTo()->wherePivot('level', '<=', $this->level())->get();
		}
		
		public function canLearn() {			
			return $this->clazz->skills()
				->wherePivot('level', '<=', $this->level())
				->get()
				->filter(function($value, $key) {
					return !$this->skills->contains('id', $value->id);
				});
		}
		
		public function skills() {		
			return $this->belongsToMany(Skill::class, 'character_skills', 'character_id', 'skill_id')
                ->as('usage')
    			->withPivot('nextUse');
		}
		
		public function maxHealth() {
			return 100;	
		}

		public function description() {
			return format('character', [$this->race->name(), $this->clazz->name()]);
		}

		public function name() {
			return $this->name;
		}

		public function participant() {
			return $this->belongsTo(Participant::class, 'participant_id');
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

			$correct = true;

			if(!$this->position) {
				$this->createPosition();
				$correct = false;
			}
			if(!$this->participant) {
				$this->createParticipant();
				$correct = false;
			}

			while($this->xp >= $this->requiredXp()) {

				$this->xp -= $this->requiredXp();
				$this->levelUp();
				$correct = false;

			}

			return $correct;
		}
		
	}
		

?>
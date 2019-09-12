<?php

	class Character extends Participant {
   		
		protected $table = 'character';
		protected $with = ['clazz', 'race', 'position', 'battle', 'inventory', 'account'];
		
		public function icon() {
			return $this->clazz->icon();
		}
		
		public function race() {
			return $this->belongsTo(Race::class, 'race_id');
		}
		
		public function addLoot($loot) {
			
			$this->xp += $loot['xp'];
			foreach($loot['items'] as $item) {
				
				$item->character_id = $this->id;
				$item->slot_id = 4;
				$item->save();
				
			}
			
			$this->save();
			
		}

		public function learn($skill) {
			if(!$skill) return false;
			global $capsule;

			if($skill) {
			
				$capsule::table('character_skills')
					->insert(['skill' => $skill->id, 'character' => $this->id]);

				return true;

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
		
		public function travel($location) {
			if(!$location) return false;
			
			if($location && $location->level <= $this->level()) {
				
				if(!$this->battle) {
				
					$pos = $this->position();
					$pos->location_id = $location->id;
					$pos->save();
					
				}
				
				return true;
				
			}
			
			return false;
		}
		
		public function level() {
			return floor(static::levelFrom($this->xp));
		}
		
		public function requiredXp($level) {
		
			return 10 * $level;
			
		}
		
		public function levelFrom($xp) {
			
			return log(max($xp, 2)) + 1;
			
			$level = 0;
			while($xp >= 0) {
				$xp -= requiredXp($level);
				$level++;
			}
			return $level;
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
			return $this->clazz->evolvesTo()->wherePivot('level', '<=', $this->level())->get();
		}
		
		public function canLearn() {			
			return $this->clazz->skills()->wherePivot('level', '<=', $this->level())->get();
		}
		
		public function skills() {		
			return $this->belongsToMany(Skill::class, 'character_skills', 'character_id', 'skill_id')
                ->as('usage')
    			->withPivot('nextUse');
		}
		
		public function maxHealth() {
			return 100;	
		}

		public function name() {
			return format('character', [$this->race->name(), $this->clazz->name()]);
		}
		
	}
		

?>
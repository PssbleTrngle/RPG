<?php

	class Status extends BaseModel {
		
		protected $table = 'status';
		
		public function accounts() {
			return $this->hasMany(Account::class, 'status');
		}
	
	}

	class Account extends BaseModel {
   		
		protected $table = 'account';
		protected $with = ['status', 'characters', 'selected'];
		
		public function status() {
			return $this->belongsTo(Status::class, 'status');
		}
		
		public function characters() {
			return $this->hasMany(Character::class, 'account')->without(['account']);
		}
		
		public function selected() {
			return $this->belongsTo(Character::class, 'selected')->without(['account']);
		}
		
		public function select($character) {
			if(is_numeric($character)) $character = Character::find($character);
			
			$do = false;
			
			if($character)
				foreach($this->relations['characters'] as $char)
					$do = $do || $char->id == $character->id;
			
			if($do) {
				$this->selected = $character->id;
				$this->save();
			}
			
			return $do;
		}
	
	}

	class Race extends BaseModel {
   		
		protected $table = 'race';
		protected $with = ['stats'];
		
		public function stats() {
			return $this->belongsTo(Stats::class, 'stats');
		}
		
	}

	class Character extends Participant {
   		
		protected $table = 'character';
		protected $with = ['clazz', 'race', 'position', 'battle', 'inventory', 'account'];
		
		public function race() {
			return $this->belongsTo(Race::class, 'race');
		}
		
		public function addLoot($loot) {
			
			$this->xp += $loot['xp'];
			foreach($loot['items'] as $item) {
				
				$item->character = $this->id;
				$item->slot = 4;
				$item->save();
				
			}
			
			$this->save();
			
		}

		public function learn($skill) {
			if(is_numeric($skill)) $skill = Skill::find($skill);
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
			return $this->belongsTo(Clazz::class, 'class');
		}
		
		public function account() {
			return $this->belongsTo(Account::class, 'account')->without('characters');
		}
		
		public function inventory() {
			return $this->hasMany(Stack::class, 'character')->without('character');
		}
		
		public function stats() {
			return $this->relations['clazz']->relations['stats']->add($this->relations['race']->relations['stats']);
		}
		
		public function position() {
			return $this->belongsTo(Position::class, 'id');
		}
		
		public function itemIn($slot) {
		
			if(is_numeric($slot)) $slot = Slot::find($slot);
			return $this->relations['inventory']->where('slot', '=', $slot->id);
			
		}
		
		public function isSelected() {
			$selected = $this->account()->first()->relations['selected'];
			return $selected && $selected->id == $this->id;
		}
		
		public function travel($location) {
			if(is_numeric($location)) $location = Location::find($location);
			
			if($location && $location->level <= $this->level()) {
				
				if(!$this->relations['battle']) {
				
					$pos = $this->position();
					$pos->location = $location->id;
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
			if(is_numeric($to)) $to = Clazz::find($to);
			
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
			return $this->relations['clazz']->evolvesTo()->wherePivot('level', '<=', $this->level())->get();
		}
		
		public function canLearn() {			
			return $this->relations['clazz']->skills()->wherePivot('level', '<=', $this->level())->get();
		}
		
		public function skills() {		
			return $this->belongsToMany(Skill::class, 'character_skills', 'character', 'skill')
                ->as('usage')
    			->withPivot('nextUse');
		}
		
		public function maxHealth() {
			return 100;	
		}
		
	}
		

?>
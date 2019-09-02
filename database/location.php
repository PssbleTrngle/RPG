<?php

	class Position extends BaseModel {
   		
		protected $table = 'position';
		protected $with = ['location', 'dungeon'];
		
		public function location() {
			return $this->belongsTo(Location::class, 'location');
		}
		
		public function dungeon() {
			return $this->belongsTo(Dungeon::class, 'dungeon');
		}
		
	}

	class Location extends BaseModel {
   		
		protected $table = 'location';
		protected $with = ['dungeons', 'area'];
		
		public function area() {
			return $this->belongsTo(Area::class, 'area');
		}
		
		public function dungeons() {
			return $this->hasMany(Dungeon::class, 'location')->without('location');
		}
		
	}

	class Area extends BaseModel {
   		
		protected $table = 'area';
		protected $with = ['locations'];
		
		public function locations() {
			return $this->hasMany(Location::class, 'area')->without(['area']);
		}
		
	}

	class Dungeon extends BaseModel {
   		
		protected $table = 'dungeon';
		protected $with = ['location', 'npcs'];
		
		public function location() {
			return $this->belongsTo(Location::class, 'location');
		}
		
		public function npcs() {
			return $this->belongsToMany(Skill::class, 'dungeon_npc', 'dungeon', 'npc')
                ->as('floor')
    			->withPivot('maxFloor')
    			->withPivot('minFloor');
		}
		
		public function getNPC($floor) {
			
			$npc = $this->npcs->random();
			return NPC::find(1);
			
		}
		
		public function leave($character) {
			if(is_numeric($character)) $character = Character::find($character);
			
			if($character && ($position = $character->position) && ($dungeon = $position->dungeon) && ($dungeon->id == $this->id)) {
			
				#$position->dungeon = null;
				$position->floor = 1;
				$position->attempts = 0;
				$position->foundStairs = 0;
				$position->save();
				return true;
				
			}
			
			return false;
			
		}
		
		public function down($character) {
			if(is_numeric($character)) $character = Character::find($character);
			
			if($character && ($position = $character->position) && ($dungeon = $position->dungeon) && ($dungeon->id == $this->id)) {
				
				if($position->floor < $dungeon->floors && $position->foundStairs) {
			
					$position->floor++;
					$position->attempts = 0;
					$position->foundStairs = 0;
					$position->save();
					return true;
					
				}
				
			}
			
			return false;
			
		}
		
		public function search($character) {
			if(is_numeric($character)) $character = Character::find($character);			
			
			if($character && ($position = $character->position)
			   && ($dungeon = $position->dungeon) && ($dungeon->id == $this->id)) {
				
				if(!$position->foundStairs && rand(1, 100) < 5 + 3*$position->attempts) {
				
					$position->foundStairs = true;
					$position->attempts++;
				
				} else if(rand(1, 100) < 80) {
					
					$position->attempts++;
					
					if($npc = $dungeon->getNPC($position->floor)) {
						$battle = Battle::start($character);
						if(!$battle) return false;
						$battle->addNPC($npc);
					}
					
				}
				
				$position->save();
				return true;
			
			}
			
			return false;
		}
		
	}

?>
<?php

	class Position extends BaseModel {
   		
		protected $table = 'position';
		
		public function location() {
			return $this->belongsTo(Location::class, 'location')->with(['area']);
		}
		
		public function dungeon() {
			return $this->belongsTo(Dungeon::class, 'dungeon')->with(['npcs']);
		}
		
	}

	class Location extends BaseModel {
   		
		protected $table = 'location';
		
		public function area() {
			return $this->belongsTo(Area::class, 'area');
		}
		
		public function dungeons() {
			return $this->hasMany(Dungeon::class, 'location')->with(['npcs']);
		}
		
	}

	class Area extends BaseModel {
   		
		protected $table = 'area';
		
		public function locations() {
			return $this->hasMany(Location::class, 'area')->with(['dungeons']);
		}
		
	}

	class Dungeon extends BaseModel {
   		
		protected $table = 'dungeon';
		
		public function location() {
			return $this->belongsTo(Location::class, 'location')->first();
		}
		
		public function npcs() {
			return $this->belongsToMany(Skill::class, 'dungeon_npc', 'dungeon', 'npc')
                ->as('floor')
    			->withPivot('maxFloor')
    			->withPivot('minFloor');
		}
		
		public function leave($character) {
			if(is_numeric($character)) $character = Character::find($character);
			
			if($character && ($position = $character->position()) && ($dungeon = $position->dungeon()) && ($dungeon->id == $this->id)) {
			
				#$position->dungeon = null;
				$position->attempts = 0;
				$position->foundStairs = 0;
				$position->save();
				return true;
				
			}
			
			return false;
			
		}
		
		public function search($character) {
			if(is_numeric($character)) $character = Character::find($character);
			
			if($character && ($position = $character->position()) && ($dungeon = $position->dungeon()) && ($dungeon->id == $this->id)) {
				
				if(!$position->foundStairs && rand(1, 100) < 5 + 3*$position->attempts) {
				
					$position->foundStairs = true;
					$position->attempts++;
				
				} else if(rand(1, 100) < 80) {
					
					$position->attempts++;
					
					if($npc = $dungeon->npcs()->get()->random()) {
						$battle = Battle::start($character);
						if(!$battle) return false;
						$battle->addNPC(NPC::find(1));
					}
					
				}
				
				$position->save();
				return true;
			
			}
			
			return false;
		}
		
	}

?>
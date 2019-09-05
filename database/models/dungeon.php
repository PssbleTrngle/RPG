<?php

	class Dungeon extends BaseModel {
   		
		protected $table = 'dungeon';
		protected $with = ['location', 'npcs'];
		
		public function location() {
			return $this->belongsTo(Location::class, 'location_id');
		}
		
		public function npcs() {
			return $this->belongsToMany(Skill::class, 'dungeon_npc', 'dungeon_id', 'npc_id')
                ->as('floor')
    			->withPivot('maxFloor')
    			->withPivot('minFloor');
		}
		
		public function getNPC($floor) {
			
			$npc = $this->npcs->random();
			return NPC::find(1);
			
		}
		
		public function leave($character) {
			if(!$character) return false;
			
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
			if(!$character) return false;
			
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
			if(!$character) return false;
			
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
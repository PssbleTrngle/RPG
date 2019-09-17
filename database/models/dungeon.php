<?php

	class Dungeon extends BaseModel {
   		
		protected $table = 'dungeon';
		protected $with = ['area', 'npcs'];

		public function icon() {
			return $this->icon ? 'position/dungeon/'.$this->icon : null;
		}
		
		public function area() {
			return $this->belongsTo(Area::class, 'area_id');
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
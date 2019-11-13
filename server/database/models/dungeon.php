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
			return $this->belongsToMany(NPC::class, 'dungeon_npc', 'dungeon_id', 'npc_id')
                ->as('floor')
    			->withPivot('maxFloor')
    			->withPivot('minFloor');
		}
		
		public function getNPC($floor) {

			$npcs = $this->npcs->where('floor.minFloor', '<=', $floor)->where('floor.maxFloor', '>=', $floor);
			return $npcs->random();
			
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
			
			if($character && ($position = $character->position) && $position->dungeon && $position->dungeon->id == $this->id) {
				
				if(!$position->foundStairs && rand(1, 100) < 5 + 3* $position->attempts) {
				
					/* FIND STAIRS */
					$position->foundStairs = true;
					$position->attempts++;
				
				} else if(rand(1, 100) < 10 - $position->attempts) {
					/* FIND LOOT */

					$character->addLoot(['items' => [
						Stack::create(Item::find(100 + rand(0, 10)), 1)
					]]);
					
				} else {
					
					/* ENCOUNTER ENEMY */
					$position->attempts++;
					
					if($npc = $position->dungeon->getNPC($position->floor)) {
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
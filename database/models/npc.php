<?php

	class Rank extends BaseModel {
		
		protected $table = 'rank';
		
	}

	class NPC extends BaseModel {
   		
		protected $table = 'npc';
		protected $with = ['loot', 'rank'];
		
		public function loot() {
			return $this->belongsToMany(Item::class, 'npc_loot', 'npc_id', 'item_id')
    			->withPivot('chance');
		}
		
		public function rank() {
			return $this->belongsTo(Rank::class, 'rank_id');
		}
		
		public function createEnemy($battle) {

			$enemy = new Enemy;
			$participant = new Participant;

			$participant->health = $this->maxHealth;
			$participant->battle_id = $battle->id;

			$participant->save();
			$participant->refresh();
			
			$enemy->npc_id = $this->id;
			$enemy->participant_id = $participant->id;
			
			$suffix = 'A';
			foreach($battle->enemies() as $other) if($other->npc->id == $npc->id)
				if($other->suffix && ord($other->suffix) >= ord($suffix)) 
					$suffix = chr(ord($other->suffix) + 1);
			
			$enemy->suffix = $suffix;
			
			$enemy->save();
			$enemy->refresh();

			return $enemy;
		}
		
	}

?>
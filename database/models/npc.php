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
		
		public function createEnemy() {
			$enemy = new Enemy;
			
			$enemy->npc_id = $this->id;
			$enemy->health = $this->maxHealth;
			
			$enemy->save();
			$enemy->refresh();
			return $enemy;
		}
		
	}

?>
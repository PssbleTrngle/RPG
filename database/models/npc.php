<?php

	class Rank extends BaseModel {
		
		protected $table = 'rank';
		
	}

	class NPC extends BaseModel {
   		
		protected $table = 'npc';
		protected $with = ['loot', 'rank', 'skills'];
		
		public function loot() {
			return $this->belongsToMany(Item::class, 'npc_loot', 'npc_id', 'item_id')
    			->withPivot('chance');
		}
		
		public function rank() {
			return $this->belongsTo(Rank::class, 'rank_id');
		}
		
		public function skills() {
			return $this->belongsToMany(Skill::class, 'npc_skills', 'npc_id', 'skill_id');
		}
		
		public function createEnemy($battle, $side) {
			global $capsule;

			$enemy = new Enemy;
			$participant = new Participant;

			$participant->health = $this->maxHealth;
			$participant->side = $side;

			$participant->save();
			$participant->refresh();

			$skills = [];
			foreach ($this->skills as $skill)
				$skills[] = ['participant_id' => $participant->id, 'skill_id' => $skill->id];
			$capsule->table('participant_skills')->insert($skills);
			
			$enemy->npc_id = $this->id;
			$enemy->participant_id = $participant->id;
			
			$suffix = 'A';

			foreach($battle->participants->where('enemy', '!=', null) as $other) if($other->enemy->npc->id == $this->id)
				if($other->suffix && ord($other->suffix) >= ord($suffix)) 
					$suffix = chr(ord($other->suffix) + 1);
			
			$enemy->suffix = $suffix;
			
			$enemy->save();
			$enemy->refresh();

			return $enemy;
		}
		
	}

?>
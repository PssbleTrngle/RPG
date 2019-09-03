<?php

	class Enemy extends Participant {
   		
		protected $table = 'enemy';
		protected $with = ['npc', 'battle'];
		
		public function name() {
			return $this->npc->name .' '. $this->suffix;
		}

		public function stats() {

			return Stats::find(1);

		}

		public function addEffect($effect) {
			global $capsule;

			$capule::table('enemy_effects')->insert(['enemy' => $this->id, 'effect' => $effect->id]);
			$this->refresh();			
			
		}
		
		public function takeTurn($battle) {
			
			if($battle && ($characters = $battle->characters)) {
				
				if(rand(1, 100) < (100 * option('call_chance'))  && $battle->enemies->where('health', '>', '0')->count() < option('max_enemies')) {
					
					$position = $battle->position;
					
					$called = $position->dungeon->getNPC($position->floor);
					$battle->addNPC($called);
					$called->save();
					
					return $this->name().' called for help';
					
				} else {
					
					$target = $characters->random();
					$damage = 5;
					
					$target->damage($damage);
					return $this->name().' dealt '.$damage.' damage to '.$target->name;
					
				}
				
				return $this->name().' was too scared to attack';
				
			}
		
			
		}
		
		public function maxHealth() {
			return $this->npc->maxHealth;
		}
		
		public function npc() {
			return $this->belongsTo(NPC::class, 'npc_id');
		}
		
		public function effects() {
			return $this->belongsToMany(Effect::class, 'enemy_id', 'effect_id');
		}
	}

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
			
			$enemy->npc = $this->id;
			$enemy->health = $this->maxHealth;
			
			$enemy->save();
			$enemy->refresh();
			return $enemy;
		}
		
	}

?>
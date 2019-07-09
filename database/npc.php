<?php

	class Enemy extends Participant {
   		
		protected $table = 'enemy';
		protected $with = ['npc', 'battle'];
		
		public function name() {
			return $this->relations['npc']->name .' '. $this->suffix;
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
			
			if($battle && ($characters = $battle->relations['characters'])) {
				
				if(rand(1, 100) < (100 * options('call_chance'))  && $battle->relations['enemies']->where('health', '>', '0')->count() < option('max_enemies')) {
					
					$position = $battle->relations['position'];
					
					$called = $position->relations['dungeon']->getNPC($position->floor);
					$battle->addNPC($called);
					$called->save();
					
					return $this->relations['npc']->name.' called for help';
					
				} else {
					$target = $characters->random();
					$damage = 5;
					
					$target->damage($damage);
					return $this->relations['npc']->name.' dealt '.$damage.'K damage to '.$target->name;
				}
				
				return $this->relations['npc']->name.' was too scared to attack';
				
			}
		
			
		}
		
		public function maxHealth() {
			return $this->relations['npc']->maxHealth;
		}
		
		public function npc() {
			return $this->belongsTo(NPC::class, 'npc');
		}
		
		public function effects() {
			return $this->belongsToMany(Effect::class, 'enemy', 'effect');
		}
	}

	class NPC extends BaseModel {
   		
		protected $table = 'npc';
		protected $with = ['loot'];
		
		public function loot() {
			return $this->belongsToMany(Item::class, 'npc_loot', 'npc', 'item')
    			->withPivot('chance');
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
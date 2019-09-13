<?php

	class Enemy extends BaseModel {
   		
		protected $table = 'enemy';
		protected $with = ['npc', 'participant'];
		
		public function icon() {
			return $this->npc->icon();
		}
		
		public function name() {
			return $this->npc->name() .' '. $this->suffix;
		}

		public function stats() {
			return Stats::find(1);
		}
		
		public function level() {
			return $this->npc->level;
		}
		
		public function takeTurn() {

			$battle = $this->participant->battle;
			
			if($battle) {
				
				if(rand(1, 100) < (100 * option('call_chance'))
					&& $battle->enemies()->where('health', '>', '0')->count() < option('max_enemies')) {
					
					$position = $battle->position;
					
					$called = $position->dungeon->getNPC($position->floor);
					$battle->addNPC($called);
					$called->save();
					
					return $this->name().' called for help';
					
				} else {
					
					$target = $battle->characters(true)->random();
					$damage = 5;
					
					$target->damage($damage);
					return $this->name().' dealt '.$damage.' damage to '.$target->name();
					
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

		public function participant() {
			return $this->belongsTo(Participant::class, 'participant_id');
		}
	}

?>
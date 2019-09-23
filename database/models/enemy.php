<?php

	class Enemy extends ParticipantModel {
   		
		protected $table = 'enemy';
		protected $with = ['npc', 'participant'];

		public function getLoot() {

			$xp = pow(1.5, $this->npc->level - 1);
			
			$items = [];
			foreach($this->npc->loot as $item)
				$items[] = Stack::create($item, 1);

			return new Loot($items, $xp);

		}
		
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
				
				$skills = $this->participant->useableSkills();

				if(rand(1, 100) < (100 * option('call_chance'))
					&& $battle->onSide($this->side)->where('health', '>', '0')->count() < option('max_enemies')) {
					
					$position = $battle->position;
					
					$called = $position->dungeon->getNPC($position->floor);
					$battle->addNPC($called);
					$called->save();
					
					return new Message('called_help', [$this->name()]);
					
				} else if($skills->count() > 0) {
					
					$side = $this->participant->side;
					$targetSide = collect($battle->sides)->reject(function ($value, $key) use($side) {
					    return $value == $side;
					})->random();

					$skill = $skills->random();
					$target = $battle->onSide($targetSide)->random();
					return $skill->use($target, $this->participant);
					
				}
				
				return new Message('scared', [$this->name()]);
				
			}
		
			
		}
		
		public function maxHealth() {
			return $this->npc->maxHealth;
		}
		
		public function npc() {
			return $this->belongsTo(NPC::class, 'npc_id');
		}

		public function validate() {

			if(!$this->participant || !$this->participant->battle) {
				$this->delete();
				return false;
			}

			return true;

		}

	}

?>
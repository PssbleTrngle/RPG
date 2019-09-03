<?php

	class Clazz extends BaseModel {
   		
		protected $table = 'class';
		protected $with = ['skills', 'stats'];
		
		public function evolvesTo() {
			return $this->belongsToMany(Clazz::class, 'evolution', 'from', 'to')
                ->as('evolution')
    			->withPivot('level')
    			->without(['evolvesTo', 'evolvesFrom']);
		}
		
		public function evolvesFrom() {
			return $this->belongsToMany(Clazz::class, 'evolution', 'to', 'from')
                ->as('evolution')
    			->withPivot('level')
    			->without(['evolvesTo', 'evolvesFrom']);
		}
		
		public function rank() {
		
			$class = $this;
			
			$rank = 1;
			for($rank = 1; ($from = $class->evolvesFrom()->first()); $rank++)
				$class = $from;
			
			return $rank;
			
		}
		
		public function skills() {
			return $this->belongsToMany(Skill::class, 'class_skills', 'class_id', 'skill_id');
		}
		
		public function stats() {
			return $this->belongsTo(Stats::class, 'stats_id');
		}
	
	}
?>
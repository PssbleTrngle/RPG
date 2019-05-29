<?php

	class Clazz extends BaseModel {
   		
		protected $table = 'class';
		
		public function evolvesTo() {
			return $this->belongsToMany(Clazz::class, 'evolution', 'from', 'to')
                ->as('evolution')
    			->withPivot('level');
		}
		
		public function evolvesFrom() {
			return $this->belongsToMany(Clazz::class, 'evolution', 'to', 'from')
                ->as('evolution')
    			->withPivot('level');
		}
		
		public function rank() {
		
			$class = $this;
			
			$rank = 1;
			for($rank = 1; ($from = $class->evolvesFrom()->first()); $rank++)
				$class = $from;
			
			return $rank;
			
		}
		
		public function skills() {
			return $this->belongsToMany(Skill::class, 'class_skills', 'class', 'skill')->get();
		}
		
		public function stats() {
			return $this->belongsTo(Stats::class, 'stats')->first();
		}
	
	}

	class Skill extends BaseModel {
   		
		protected $table = 'skill';
		
		public function learnedBy() {
			return $this->belongsToMany(Clazz::class, 'class_skills', 'skill', 'class')->get();
		}
		
		public static $functions = array();		
		public function register($request, $function) {
			
			$id = $request['id'];
			$model = static::find($id) ?? new static;
			
			foreach($request as $key => $param)
				$model->$key = $request[$key];
			$model->save();			
		
			Skill::$functions[$id] = $function;
		
		}
		
		public function timeout($character) {
			if(is_numeric($character)) $character = Character::find($character);
			global $capsule;
			
			if($character) {
			
				$capsule::table('character_skills')
					->where('character', $character->id)
					->where('skill', $this->id)
					->update(['nextUse' => $this->timeout]);
				
			}			
		}
			
		public function apply($target, $user) {
			$func = Skill::$functions[$this->id];
			
			if(!$func) return false;
			
			return $func($target, $user);
		}
	
		public function registerAll() {
			
			Skill::register(['id' => 1, 'name' => "Heal", 'timeout' => 5, 'cost' => 2], function($target, $user) {
				if(method_exists($target, 'heal')) {
					$health = 15;
					return $target->heal($health) ? 'You healt '.$target->name.' by '.$health.'K' : 'The spell failed!';
				}
				return false;
			});
			
			Skill::register(['id' => 2, 'name' => 'Glow', 'timeout' => 5, 'cost' => 1], function($target, $user) {});
			
			Skill::register(['id' => 3, 'name' => 'Pulse', 'timeout' => 3, 'cost' => '1'], function($target, $user) {
				if(method_exists($target, 'damage')) {
					$damage = 5;
					return $target->damage($damage) ? 'The pulse dealt '.$damage.'K damage to '.$target->name : 'The attack had no effect!';
				}
				return false;
			});
			
		}
		
	}	

?>
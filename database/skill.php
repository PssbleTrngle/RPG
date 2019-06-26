<?php

	class Skill extends BaseModel {
   		
		protected $table = 'skill';
		
		public static $functions = array();		
		public static function register($request, $function) {
			
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
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => "Heal", 'timeout' => 0, 'cost' => 2, 'group' => false], function($target, $user) {
				if(method_exists($target, 'heal')) {
					$health = 15;
					return $target->heal($health) ? 'You healed '.$target->name().' by '.$health.'K' : 'The spell failed!';
				}
				return false;
			});
			
			static::register(['id' => 2, 'name' => 'Glow', 'timeout' => 0, 'cost' => 1, 'group' => true], function($targets, $user) {});
			
			static::register(['id' => 3, 'name' => 'Pulse', 'timeout' => 0, 'cost' => 1, 'group' => false], function($target, $user) {
				if(method_exists($target, 'damage')) {
					$damage = 10;
					return $target->damage($damage) ? 'The pulse dealt '.$damage.'K damage to '.$target->name() : 'The attack had no effect!';
				}
				return false;
			});
			
			static::register(['id' => 4, 'name' => 'Rumble', 'timeout' => 0, 'cost' => 1, 'group' => true], function($targets, $user) {
				$msg = '';
				foreach($targets as $target) {
					if(method_exists($target, 'damage')) {
						$damage = 8;
						if($target->damage($damage)) $msg .= 'The rumble dealt '.$damage.'K damage to '.$target->name().'\n';
					}
				}
				return $msg != '' ? $msg : false;
			});
			
		}
		
	}	

?>
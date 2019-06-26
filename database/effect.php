<?php

	class Effect extends BaseModel {
   		
		protected $table = 'effect';
		
		public static $functions = array();
		public static function register($request, $function) {
			
			$id = $request['id'];
			$model = static::find($id) ?? new static;
			
			foreach($request as $key => $param)
				$model->$key = $request[$key];
			$model->save();			
		
			Effect::$functions[$id] = $function;
		
		}
			
		public function apply($target) {
			$func = Skill::$functions[$this->id];
			
			if(!$func) return false;
			
			return $func($target);
		}
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => 'Poison'], function($target) {
				if(method_exists($target, 'damage')) {
					$damage = 2;
					return $target->damage($damage) ? true : false;
				}
				return false;
			});
			
		}
		
	}	

?>
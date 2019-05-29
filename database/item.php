<?php

	class Inventory extends BaseModel {
   		
		protected $table = 'inventory';
		
		public function item() {
			return $this->belongsTo(Item::class, 'item')->first();
		}
		
		public function slot() {
			return $this->belongsTo(Slot::class, 'slot')->first();
		}
		
		public function character() {
			return $this->belongsTo(Character::class, 'character')->first();
		}
		
		public function enchantment() {
			return $this->belongsTo(Enchantment::class, 'enchantment')->first();
		}
		
	}

	class ItemType extends BaseModel {
   		
		protected $table = 'itemtype';
		
		public function items() {
			return $this->hasMany(Item::class, 'type')->get();
		}
		
	}

	class Enchantment extends BaseModel {
   		
		protected $table = 'enchantment';
		
	}

	class Item extends BaseModel {
   		
		protected $table = 'item';
		
		public function type() {
			return $this->belongsTo(ItemType::class, 'type')->first();
		}
		
	}

	class Slot extends BaseModel {
   		
		protected $table = 'slot';
		
		function accept(Item $item) {
			return $functions[$this->id]($item);			
		}
		
		public static $functions = array();
		public static function register($request, $function) {
			
			if(!Slot::find($request['id'])) {
			
				$model = new Slot;
				foreach($request as $key => $param)
					$model->$key = $request[$key];
				$model->save();
			
			}
			
			$functions[$request['id']] = $function;
		
		}
	
		public static function registerAll() {
			
			Slot::register(['id' => 1, 'name' => "Inventory"], function($item) { return true; });			
			Slot::register(['id' => 2, 'name' => "Left Hand"], function($item) { return false; });
			Slot::register(['id' => 3, 'name' => "Right Hand"], function($item) { return false; });
			
		}
		
	}		

?>
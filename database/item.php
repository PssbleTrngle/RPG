<?php

	class Stack extends BaseModel {
   		
		protected $table = 'inventory';
		protected $with = ['item', 'slot', 'character', 'enchantment'];
		
		public function item() {
			return $this->belongsTo(Item::class, 'item_id');
		}
		
		public function slot() {
			return $this->belongsTo(Slot::class, 'slot_id');
		}
		
		public function character() {
			return $this->belongsTo(Character::class, 'character_id');
		}
		
		public function enchantment() {
			return $this->belongsTo(Enchantment::class, 'enchantment_id');
		}
		
		public static function tidy($character) {
			if(is_numeric($character)) $character = Character::find($character);
			
			if($character) {
			
				foreach([1, 4] as $slot) {
					
					$stacked = [];
					
					foreach($character->inventory as $stack) if($stack->slot->id == $slot) {
						
						if($stack->item->stackable) {
							if(array_key_exists($stack->item->id, $stacked)) {

								$stacked[$stack->item]->amount += $stack->amount;
								$stack->delete();

							} else $stacked[$stack->item->id] = $stack;
						}

					}
					
					foreach($stacked as $stack) $stack->save();
				}
				
				$character->refresh();
			
			}
		}
		
		public function take($character) {
			if(is_numeric($character)) $character = Character::find($character);
			
			if($character) {			
				if($this->character == $character->id) {
					if($this->slot != 1) {
						
						$hasSpace = $character->itemIn(1)->count() < $character->bagSize();
						
						if(!$hasSpace && $this->stackable) foreach($character->itemIn(1) as $stack)
							if($stack->item == $this->item) {
								$hasSpace = true;
								break;
							}
						
						if($hasSpace) {
						
							$this->slot = 1;
							$this->save();
							$character->refresh();
							Stack::tidy($character);
							
							return true;
							
						}
						
					}
				}			
			}
			
			return false;
			
		}
		
	}

	class ItemType extends BaseModel {
   		
		protected $table = 'itemtype';
		protected $with = ['items'];
		
		public function items() {
			return $this->hasMany(Item::class, 'type_id')->without(['type']);
		}
		
	}

	class Enchantment extends BaseModel {
   		
		protected $table = 'enchantment';
		
	}

	class Item extends BaseModel {
   		
		protected $table = 'item';
		protected $with = ['type'];
		
		public function type() {
			return $this->belongsTo(ItemType::class, 'type_id')->without(['items']);
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
			Slot::register(['id' => 4, 'name' => "Loot"], function($item) { return false; });
			
		}
		
	}		

?>
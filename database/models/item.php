<?php

	class Stack extends BaseModel {
   		
		protected $table = 'inventory';
		protected $with = ['item', 'slot', 'enchantment'];
		
		public function item() {
			return $this->belongsTo(Item::class, 'item_id');
		}
		
		public function name() {
			return $this->amount.'x '.$this->item->name();
		}
		
		public function icon() {
			return $this->item->icon();
		}
		
		public function slot() {
			return $this->belongsTo(Slot::class, 'slot_id');
		}
		
		public function enchantment() {
			return $this->belongsTo(Enchantment::class, 'enchantment_id');
		}
		
		public static function tidy($character) {
			if(!$character) return false;
			
			if($character) {
			
				foreach([1, 4] as $slot) {
					
					$stacked = [];
					
					foreach($character->inventory as $stack) if($stack->slot->id == $slot) {
						
						if($stack->item->stackable) {
							if(array_key_exists($stack->item->id, $stacked)) {

								$stacked[$stack->item->id]->amount += $stack->amount;
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
			if(!$character) return false;

			$slot = Slot::where('name', 'inventory')->first();
			
			if($character) {
				if($this->character_id == $character->id) {
					if($this->slot_id != $slot->id) {
						
						/* TODO test */
						$hasSpace = $character->itemIn($slot)->count() < $character->bagSize()
						|| $character->itemIn($slot)->where('stackable', true)->contains('item_id', $this->item->id);
						
						/*
						if(!$hasSpace && $this->stackable) foreach($character->itemIn($slot) as $stack)
							if($stack->item_id == $this->item_id) {
								$hasSpace = true;
								break;
							}
						*/

						if($hasSpace) {
						
							$this->slot_id = 1;
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

	class Rarity extends BaseModel {
   		
		protected $table = 'rarity';
		protected $with = ['items'];
		
		public function items() {
			return $this->hasMany(Item::class, 'rarity_id')->without(['rarity']);
		}
		
	}

	class ItemType extends BaseModel {
   		
		protected $table = 'itemtype';
		protected $with = ['items', 'parents'];
		
		public function items() {
			return $this->hasMany(Item::class, 'type_id')->without(['type']);
		}
		
		public function parents() {
			return $this->belongsToMany(ItemType::class, 'itemtype_relations', 'child', 'parent');
		}

		public function anchestors() {

			$anchestors = [];

			foreach($this->parents as $parent) {
				$anchestors[] = $parent;
				$anchestors = array_merge($anchestors, $parent->anchestors());
			}

			return $anchestors;

		}
		
	}

	class Enchantment extends BaseModel {
   		
		protected $table = 'enchantment';
		
	}

	class Item extends BaseModel {
   		
		protected $table = 'item';
		protected $with = ['type', 'rarity'];

		public function color() {
			return $this->rarity->color;
		}

		public function icon() {

			foreach ($this->types() as $type)
				if($type->icon) return $this->table.'/'.$type->name.'/'.$this->name;

			return $this->table.'/'.$this->name;
		}
		
		public function name() {

			$name = format(implode('.', explode('/', $this->icon())));
			if($this->color()) return format('item.weapon.format', [$name, $this->rarity->name()]);

			return $name;
		}
		
		public function type() {
			return $this->belongsTo(ItemType::class, 'type_id')->without(['items']);
		}
		
		public function rarity() {
			return $this->belongsTo(Rarity::class, 'rarity_id')->without(['items']);
		}

		public function hasType($type) {
			foreach ($this->types as $has)
				if($has->id == $type->id)
					return true;
			return false;
		}

		public function types() {

			$types = $this->type->anchestors();
			$types[] = $this->type;

			return array_reverse($types);

		}
	
		public static function registerAll() {
			
			foreach(Rarity::whereNotNull('color')->get() as $cent => $rank)
				foreach(['Blade', 'Bow', 'Florett', 'Maze', 'Nunchuck', 'Sceptre', 'Wand', 'Battlestaff', 'Club', 'Dagger', 'Hammer'] as $i => $weapon) {

					$rarity = Rarity::where('name', $rank)->first();
					if($rarity) {
						$type = ItemType::where('name', $weapon)->first() ?? ItemType::find(3);
						static::register(['id' => (100 * ($cent + 1)) + $i, 'name' => strtolower($weapon), 'rarity_id' => $rarity->id, 'type_id' => $type->id, 'stackable' => 0]);
					}
			}
			
		}
		
	}

	class Slot extends BaseModel {
   		
		protected $table = 'slot';
		
		function accept(Item $item) {
			return $functions[$this->id]($item);			
		}
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => "Inventory", 'space' => 20], ['fits' => function($item) { return true; }]);
			static::register(['id' => 2, 'name' => "Left Hand", 'space' => 1], ['fits' => function($item) { return false; }]);
			static::register(['id' => 3, 'name' => "Right Hand", 'space' => 1], ['fits' => function($item) { return false; }]);
			static::register(['id' => 4, 'name' => "Loot", 'space' => 20], ['fits' => function($item) { return false; }]);
			
		}
		
	}		

?>
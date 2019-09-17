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
		
		public function take($character, $slot) {
			if(!$character) return false;

			$slot = Slot::where('name', 'inventory')->first();
			
			if($character) {
				if($this->character_id == $character->id) {
					if($this->slot_id != $slot->id) {
						
						/* TODO test */
						/*
						$hasSpace = $character->itemIn($slot)->count() < $slot->space
						|| $character->itemIn($slot)->where('stackable', true)->contains('item_id', $this->item->id);
						*/

						/*
						if(!$hasSpace && $this->stackable) foreach($character->itemIn($slot) as $stack)
							if($stack->item_id == $this->item_id) {
								$hasSpace = true;
								break;
							}
						*/
						
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

		public function name() {

			$name = $this->table.'.';
			foreach ($this->anchestors() as $anchestor) 
				if($anchestor->icon)
					$name .= $anchestor->name.'s.';

			$name .= $this->name;

			return format($name);
		}
		
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
			if($this->color()) return format('item.weapon.format', [$this->type->name(), $this->rarity->name()]);

			return $name;
		}
		
		public function type() {
			return $this->belongsTo(ItemType::class, 'type_id')->without(['items']);
		}
		
		public function rarity() {
			return $this->belongsTo(Rarity::class, 'rarity_id')->without(['items']);
		}

		public function hasType($type) {
			if(is_string($type)) return $this->types()->contains('name', $type);
			return $this->types()->contains('id', $type->id);
		}

		public function types() {

			$types = $this->type->anchestors();
			$types[] = $this->type;

			return collect(array_reverse($types));

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

		function fits(Stack $stack, Character $character) {

			$items = $character->itemIn($this);
			$singular = $this->space == 1;

			if($stack->slot && $stack->slot->id == $this->id) 
				return ['success' => false, 'message' => 'Already in this slot'];

			if($this->locked($character)) 
				return ['success' => false, 'message' => 'Slot is locked'];

			if($singular) {
				if($items) return ['success' => false, 'message' => 'Already equiped something'];
			} else {
				if($items->count() >= $this->space)
					return ['success' => false, 'message' => 'Not enough space'];
			}

			return result($this->__call('fits', [$stack, $character]));

		}
	
		public static function registerAll() {
			
			static::register(['id' => 1, 'name' => "inventory", 'space' => 20], ['fits' => function($slot, $stack, $character) { return true; }]);

			static::register(['id' => 2, 'name' => "left_hand", 'space' => 1], ['fits' => function($slot, $stack, $character) {

				$other = $character->itemIn('right_hand');
				$isWeapon = $stack->item->hasType('weapon');
				$locked = $other && $stack->item->hasType('two_handed');
				return $isWeapon && !$locked;

			}, 'locked' => function($slot, $character) {

				$other = $character->itemIn('right_hand');
				return $other && $other->item->hasType('two_handed');

			}]);

			static::register(['id' => 3, 'name' => "right_hand", 'space' => 1], ['fits' => function($slot, $stack, $character) {

				$other = $character->itemIn('left_hand');
				$isWeapon = $stack->item->hasType('weapon');
				$locked = $other && $stack->item->hasType('two_handed');
				return $isWeapon && !$locked;

			}, 'locked' => function($slot, $character) {

				$other = $character->itemIn('left_hand');
				return $other && $other->item->hasType('two_handed');

			}]);

			static::register(['id' => 4, 'name' => "loot", 'space' => 20], ['fits' => function($slot, $stack, $character) { return false; }]);
			
		}
		
	}		

?>
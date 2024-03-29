<?php

	class Stack extends BaseModel {
   		
		protected $table = 'inventory';
		protected $with = ['item', 'slot', 'enchantment'];

		public static function create(Item $item, int $amount = 1) {
			
			$stack = new static;
			$stack->item_id = $item->id;
			$stack->amount = $amount;
			return $stack;

		}
		
		public function item() {
			return $this->belongsTo(Item::class, 'item_id');
		}
		
		public function key() {
			return new Translation('item.format', [$this->amount, $this->item->name()]);
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
		
		/* TODO integrate */
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

		public function useUp() {

			if($this->amount == 0)
				$this->delete();

			else {
				$this->amount--;
				$this->save();
			}

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

		public function key() {

			$name = $this->table.'.';
			foreach ($this->anchestors() as $anchestor) 
				if($anchestor->icon)
					$name .= $anchestor->name.'s.';

			$name .= $this->name;

			return $name;
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
		protected $with = ['type', 'rarity', 'stats'];

		public function color() {
			return $this->rarity->color;
		}

		public function icon() {

			foreach ($this->types() as $type)
				if($type->icon) return $this->table.'/'.$type->name.'/'.$this->name;

			return $this->table.'/'.$this->name;
		}
		
		public function key() {

			$key = implode('.', explode('/', $this->icon()));
			if($this->color()) return new Translation('item.weapon.format', [$this->type->name(), $this->rarity->name()]);

			return $key;
		}
		
		public function type() {
			return $this->belongsTo(ItemType::class, 'type_id')->without(['items']);
		}
		
		public function stats() {
			return $this->belongsTo(Stats::class, 'stats_id');
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

			$keys = ['wisdom', 'strength', 'agility', 'luck', 'resistance'];
			$weapons = ['blade', 'bow', 'florett', 'maze', 'nunchuck', 'sceptre', 'wand', 'battlestaff', 'club', 'dagger', 'hammer'];

			foreach(Rarity::whereNotNull('color')->get() as $cent => $rarity)
				foreach($weapons as $i => $weapon) {

					$id = (100 * ($cent + 1)) + $i;

					$base = Stats::find(100 + $i);
					$stats = Stats::find($id);
					if($base && $stats) {
						foreach ($keys as $key)
							if($base->$key < 0)
							$stats->$key = $base->$key - $cent;
							else
								$stats->$key = $base->$key * ($cent + 1);
						$stats->save();
					}

					$type = ItemType::where('name', $weapon)->first() ?? ItemType::find(3);
					static::register(['id' => $id, 'stats_id' => $id, 'name' => $weapon, 'rarity_id' => $rarity->id, 'type_id' => $type->id, 'stackable' => 0]);
					
			}

			static::register(['id' => 1, 'name' => 'health_potion', 'type_id' => 2, 'stackable' => 1], ['use' => function(Item $item, Stack $stack, Participant $target) {
				if($target->heal(20)) {
					$stack->useUp();
					return true;
				}
				return false;
			}]);

			static::register(['id' => 2, 'name' => 'sleep_potion', 'type_id' => 2, 'stackable' => 1], ['use' => function(Item $item, Stack $stack, Participant $target) {
				if($target->addEffect(Effect::find(6))) {
					$stack->useUp();
					return true;
				}
				return false;
			}]);

			static::register(['id' => 3, 'name' => 'poison_potion', 'type_id' => 2, 'stackable' => 1], ['use' => function(Item $item, Stack $stack, Participant $target) {
				if($target->addEffect(Effect::find(1))) {
					$stack->useUp();
					return true;
				}
				return false;
			}]);

			static::register(['id' => 10, 'name' => 'ambrosia', 'type_id' => 2, 'stackable' => 1], ['use' => function(Item $item, Stack $stack, Participant $target) {
				$removed = false;
				foreach ($target->effects as $effect)
					$removed = $removed || $target->removeEffect($effect);
				if($removed) $stack->useUp();
				return $removed;
			}]);

			static::register(['id' => 4, 'name' => 'honey', 'type_id' => 1, 'stackable' => 1]);
			
		}
		
	}

?>
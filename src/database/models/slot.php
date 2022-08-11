<?php

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
<?php

	interface Target {
   		
		public function damage(DamageEvent $event);
   		
		public function heal($amount);
   		
		public function revive(Participant $by = null);
   		
		public function addEffect($effect);
	
	}

?>
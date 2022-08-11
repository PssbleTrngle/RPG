<?php

	class Race extends BaseModel {
   		
		protected $table = 'race';
		protected $with = ['stats'];
		
		public function stats() {
			return $this->belongsTo(Stats::class, 'stats_id');
		}
		
	}

?>
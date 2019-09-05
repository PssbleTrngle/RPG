<?php

	class Position extends BaseModel {
   		
		protected $table = 'position';
		protected $with = ['location', 'dungeon'];
		
		public function location() {
			return $this->belongsTo(Location::class, 'location_id');
		}
		
		public function dungeon() {
			return $this->belongsTo(Dungeon::class, 'dungeon_id');
		}
		
	}

	class Location extends BaseModel {
   		
		protected $table = 'location';
		protected $with = ['dungeons', 'area'];
		
		public function area() {
			return $this->belongsTo(Area::class, 'area_id');
		}
		
		public function dungeons() {
			return $this->hasMany(Dungeon::class, 'location_id')->without(['location']);
		}
		
	}

	class Area extends BaseModel {
   		
		protected $table = 'area';
		protected $with = ['locations'];
		
		public function locations() {
			return $this->hasMany(Location::class, 'area_id')->without(['area']);
		}
		
	}

?>
<?php

	class Position extends BaseModel {
   		
		protected $table = 'position';
		protected $with = ['location', 'dungeon'];

		public function icon() {
			
			if($this->dungeon && $this->dungeon->icon())
				return $this->dungeon->icon();
			
			return $this->location->icon() ?? $this->location->area->icon();

		}

		public function name() {
			
			if($this->dungeon)
				return $this->dungeon->name();
			
			return $this->location->name();

		}
		
		public function location() {
			return $this->belongsTo(Location::class, 'location_id');
		}
		
		public function dungeon() {
			return $this->belongsTo(Dungeon::class, 'dungeon_id');
		}
		
	}

	class Location extends BaseModel {
   		
		protected $table = 'location';
		protected $with = ['area'];

		public function icon() {
			return $this->icon ? 'position/location/'.$this->icon : null;
		}
		
		public function area() {
			return $this->belongsTo(Area::class, 'area_id');
		}
		
	}

	class Area extends BaseModel {
   		
		protected $table = 'area';
		protected $with = ['locations', 'dungeons'];

		public function icon() {
			return $this->icon ? 'position/area/'.$this->icon : null;
		}
		
		public function locations() {
			return $this->hasMany(Location::class, 'area_id')->without(['area']);
		}
		
		public function dungeons() {
			return $this->hasMany(Dungeon::class, 'area_id')->without(['area']);
		}
		
	}

?>
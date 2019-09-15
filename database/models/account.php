<?php

	class Status extends BaseModel {
		
		protected $table = 'status';
		
		public function accounts() {
			return $this->hasMany(Account::class, 'status_id');
		}
	
	}

	class Account extends BaseModel {
   		
		protected $table = 'account';
		protected $with = ['status', 'characters', 'selected'];
		
		public function status() {
			return $this->belongsTo(Status::class, 'status_id');
		}
		
		public function name() {
			return $this->username;
		}
		
		public function icon() {
			return $this->selected ? $this->selected->icon() : false;
		}
		
		public function characters() {
			return $this->hasMany(Character::class, 'account_id')->without(['account']);
		}
		
		public function selected() {
			return $this->belongsTo(Character::class, 'selected_id')->without(['account']);
		}
		
		public function select($character) {

			if($character && $this->characters->contains('id', $character->id)) {
				$this->selected_id = $character->id;
				$this->save();
				return true;
			}
			
			return false;
		}

		public function hasStatus($status) {
			if(is_string($status)) $status = Status::where('name', $status)->first();
	        return $status && $this->status->id >= $status->id;
		}

		public function validate() {

			if($this->selected && $this->id != $this->selected->account_id) {
				$this->selected_id = null;
				$this->save();
				return false;
			}

			return true;

		}
	
	}
		

?>
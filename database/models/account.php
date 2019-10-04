<?php

	class Permission extends BaseModel {
		
		protected $table = 'permission';
	
	}

	class Account extends BaseModel {
   		
		protected $table = 'account';
		protected $with = ['permissions', 'characters', 'selected'];
		
		public function permissions() {
			return $this->belongsToMany(Permission::class, 'account_permissions', 'account_id', 'permission_id');
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

		public function hasPermission($permission) {
			if(is_string($permission))
				return $this->permissions->contains('name', $permission);
			return $this->permissions->contains('id', $permission->id);
		}

		public function validate() {

			if($this->selected && $this->id != $this->selected->account_id) {
				$this->selected_id = null;
				$this->save();
				return false;
			}

			else if(!$this->selected && !$this->characters->isEmpty()) {
				$this->selected_id = $this->characters->first()->id;
				$this->save();
				return false;
			}

			return true;

		}
		
		public function addPermission($permission) {
			global $capsule;

			if(!$this->permissions->contains('id', $permission->id)) {
				$capsule->table('account_permissions')->insert(['account_id' => $this->id, 'permission_id' => $permission->id]);
				return true;
			}

			return false;
			
		}
	
	}
		

?>
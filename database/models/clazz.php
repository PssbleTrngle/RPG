<?php

	class Clazz extends BaseModel {

		protected $table = 'class';
		protected $with = ['skills', 'stats', 'starting_weapon', 'evolvesTo', 'evolvesFrom'];
		
		public function evolvesTo() {
			return $this->belongsToMany(Clazz::class, 'evolution', 'from', 'to')
                ->as('evolution')
    			->withPivot('level')
    			->without(['evolvesTo', 'evolvesFrom']);
		}
		
		public function evolvesFrom() {
			return $this->belongsToMany(Clazz::class, 'evolution', 'to', 'from')
                ->as('evolution')
    			->withPivot('level')
    			->without(['evolvesTo', 'evolvesFrom']);
		}
		
		public function rank() {
		
			if($this->evolvesFrom->isEmpty()) return 1;
			return $this->evolvesFrom->first()->rank() + 1;
			
		}
		
		public function skills() {
			return $this->belongsToMany(Skill::class, 'class_skills', 'class_id', 'skill_id')->withPivot('level');
		}
		
		public function starting_weapon() {
			return $this->belongsTo(Item::class, 'start_weapon_id');
		}
		
		public function stats() {
			return $this->belongsTo(Stats::class, 'stats_id');
		}

		public static function registerAll() {

			return;
			/* The code below is for dev uses only */

			$classes = [
				['id' => 1, 'name' => 'apprentice'],
				['id' => 2, 'name' => 'traveller'],
				['id' => 3, 'name' => 'wild'],
				['id' => 4, 'name' => 'fighter'],
				['id' => 5, 'name' => 'psychic'],

				['id' => 11, 'name' => 'druid', 'from' => [1]],
				['id' => 12, 'name' => 'mage', 'from' => [1]],
				['id' => 13, 'name' => 'alchemist', 'from' => [1]],

				['id' => 41, 'name' => 'duelist', 'from' => [4]],
				['id' => 42, 'name' => 'warrior', 'from' => [4, 3]],
				['id' => 43, 'name' => 'focused', 'from' => [4]],

				['id' => 31, 'name' => 'ranger', 'from' => [3]],
				['id' => 32, 'name' => 'hermit', 'from' => [3]],

				['id' => 21, 'name' => 'rogue', 'from' => [2]],
				['id' => 22, 'name' => 'barde', 'from' => [2]],
				['id' => 23, 'name' => 'merchant', 'from' => [2]],

				['id' => 51, 'name' => 'priest', 'from' => [5]],
				['id' => 52, 'name' => 'medium', 'from' => [5]],
				['id' => 53, 'name' => 'telepath', 'from' => [5]],

				['id' => 101, 'name' => 'shaman', 'from' => [11, 32]],
				['id' => 102, 'name' => 'necromancer', 'from' => [11, 52]],
				['id' => 104, 'name' => 'wizard', 'from' => [12, 53]],
				['id' => 105, 'name' => 'elementalist', 'from' => [12, 13]],
				['id' => 106, 'name' => 'infused', 'from' => [13]],
				['id' => 107, 'name' => 'ritualist', 'from' => [13, 51]],
				['id' => 108, 'name' => 'astromancer', 'from' => [11]],
				['id' => 109, 'name' => 'warlock', 'from' => [12]],
				['id' => 110, 'name' => 'illusionist', 'from' => [53]],

				['id' => 401, 'name' => 'rebel', 'from' => [41]],
				['id' => 402, 'name' => 'hero', 'from' => [41]],
				['id' => 403, 'name' => 'knight', 'from' => [41]],
				['id' => 404, 'name' => 'berserk', 'from' => [42]],
				['id' => 405, 'name' => 'mercenary', 'from' => [42]],
				['id' => 406, 'name' => 'blockade', 'from' => [43]],

				['id' => 301, 'name' => 'tamer', 'from' => [31]],
				['id' => 303, 'name' => 'hunter', 'from' => [31]],
				['id' => 304, 'name' => 'monk', 'from' => [32, 43]],

				['id' => 201, 'name' => 'assassin', 'from' => [21, 31]],
				['id' => 202, 'name' => 'derwish', 'from' => [21, 43]],
				['id' => 203, 'name' => 'bandit', 'from' => [21, 32]],
				['id' => 204, 'name' => 'inventor', 'from' => [23]],
				['id' => 205, 'name' => 'fool', 'from' => [22]],
				['id' => 206, 'name' => 'performer', 'from' => [22]],
				['id' => 207, 'name' => 'charmer', 'from' => [22, 52]],
				['id' => 208, 'name' => 'artificer', 'from' => [23]],

				['id' => 501, 'name' => 'cleric', 'from' => [51, 42]],
				['id' => 502, 'name' => 'thaumaturge', 'from' => [52]],
				['id' => 504, 'name' => 'temporal', 'from' => [53]],

				['id' => 1001, 'name' => 'possessed', 'from' => [107, 404]],
				['id' => 1002, 'name' => 'sage', 'from' => [104, 304]],
				['id' => 1003, 'name' => 'beast', 'from' => [301, 106]],
				['id' => 1004, 'name' => 'fallen', 'from' => [403]],
				['id' => 1005, 'name' => 'guardian', 'from' => [403, 101, 109, 406]],
				['id' => 1006, 'name' => 'chosen', 'from' => [402]],
				['id' => 1007, 'name' => 'insane', 'from' => [205]],
				['id' => 1008, 'name' => 'narrator', 'from' => [205, 110]],
				['id' => 1009, 'name' => 'imposter', 'from' => [206, 502]],
				['id' => 1010, 'name' => 'king', 'from' => [401, 207]],
				['id' => 1011, 'name' => 'spirit', 'from' => [105]],

				['id' => 1012, 'name' => 'touched', 'from' => [501, 108]],
				['id' => 1013, 'name' => 'oracle', 'from' => [504]],
				['id' => 1014, 'name' => 'slayer', 'from' => [303]],
				['id' => 1016, 'name' => 'creator', 'from' => [204, 102]],
				['id' => 1017, 'name' => 'corrupted', 'from' => [107, 402]],
				['id' => 1018, 'name' => 'conquerer', 'from' => [203, 109]],
			];

			global $capsule;

			$capsule->table('evolution')->where('from', '>', 0)->delete();
			#static::where('id', '>', 1)->delete();

			foreach($classes as $class) {

				$object = $class;
				if(array_key_exists('from', $object)) unset($object['from']);

				static::register($object);

			}

			$evolutions = [];

			foreach($classes as $class) {

				$from = $class['from'] ?? [];
				$level = floor(log10($class['id'])) * 10;
				foreach($from as $f) $evolutions[] = ['to' => $class['id'], 'from' => $f, 'level' => $level];

			}

			$capsule->table('evolution')->insert($evolutions);

		}
	
	}
	
?>
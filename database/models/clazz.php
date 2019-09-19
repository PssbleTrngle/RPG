<?php

	class Clazz extends BaseModel {

		protected $table = 'class';
		protected $with = ['skills', 'stats', 'starting_weapon'];
		
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
		
			$class = $this;
			
			$rank = 1;
			for($rank = 1; ($from = $class->evolvesFrom()->first()); $rank++)
				$class = $from;
			
			return $rank;
			
		}
		
		public function skills() {
			return $this->belongsToMany(Skill::class, 'class_skills', 'class_id', 'skill_id');
		}
		
		public function starting_weapon() {
			return $this->belongsTo(Item::class, 'starting_weapon_id');
		}
		
		public function stats() {
			return $this->belongsTo(Stats::class, 'stats_id');
		}

		public static function registerAll() {

			/*

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
				['id' => 23, 'name' => 'smith', 'from' => [2]],

				['id' => 51, 'name' => 'priest', 'from' => [5]],
				['id' => 52, 'name' => 'medium', 'from' => [5]],
				['id' => 53, 'name' => 'telepath', 'from' => [5]],

				['id' => 101, 'name' => 'shaman', 'from' => [11, 32]],
				['id' => 102, 'name' => 'necromancer', 'from' => [11, 52]],
				['id' => 104, 'name' => 'wizard', 'from' => [12, 53]],
				['id' => 105, 'name' => 'elementalist', 'from' => [12, 13]],
				['id' => 106, 'name' => 'infused', 'from' => [13]],
				['id' => 107, 'name' => 'ritualist', 'from' => [13, 51]],

				['id' => 401, 'name' => 'rebel', 'from' => [41]],
				['id' => 402, 'name' => 'hero', 'from' => [41]],
				['id' => 403, 'name' => 'knight', 'from' => [42]],
				['id' => 404, 'name' => 'berserk', 'from' => [42]],

				['id' => 301, 'name' => 'tamer', 'from' => [31]],
				['id' => 303, 'name' => 'hunter', 'from' => [31]],
				['id' => 304, 'name' => 'monk', 'from' => [32, 23]],

				['id' => 201, 'name' => 'assassin', 'from' => [21]],
				['id' => 202, 'name' => 'swift', 'from' => [21, 43]],
				['id' => 203, 'name' => 'inventor', 'from' => [23]],
				['id' => 204, 'name' => 'fool', 'from' => [22]],
				['id' => 205, 'name' => 'performer', 'from' => [22]],

				['id' => 501, 'name' => 'cleric', 'from' => [51, 42]],
				['id' => 502, 'name' => 'thaumaturge', 'from' => [52]],
				['id' => 503, 'name' => 'summoner', 'from' => [52]],

				['id' => 1001, 'name' => 'driven', 'from' => [107, 404]],
				['id' => 1002, 'name' => 'sage', 'from' => [104, 304]],
				['id' => 1003, 'name' => 'beast', 'from' => [301, 107]],
				['id' => 1004, 'name' => 'fallen', 'from' => [403]],
				['id' => 1005, 'name' => 'guardian', 'from' => [403]],
				['id' => 1006, 'name' => 'chosen', 'from' => [402]],
				['id' => 1007, 'name' => 'insane', 'from' => [204]],
				['id' => 1008, 'name' => 'narrator', 'from' => [204]],
				['id' => 1009, 'name' => 'imposter', 'from' => [205, 502]],
				['id' => 1010, 'name' => 'king', 'from' => [401]]
			];

			global $capsule;

			$capsule->table('evolution')->where('from', '>', 0)->delete();
			static::where('id', '>', 1)->delete();

			foreach($classes as $class) {

				$object = $class;
				if(array_key_exists('from', $object)) unset($object['from']);

				static::register($object);

			}

			$evolutions = [];

			foreach($classes as $class) {

				$from = $class['from'] ?? [];
				foreach($from as $f) $evolutions[] = ['to' => $class['id'], 'from' => $f, 'level' => 1];

			}

			$capsule->table('evolution')->insert($evolutions);

			*/

		}
	
	}
	
?>
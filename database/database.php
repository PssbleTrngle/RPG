<?php

	/*
		Handles everything related to the MySQL Database
		by using the Illuminate Database Model
		The various classes are imported at the bottom
	*/

	include_once 'options.php';

	use Illuminate\Database\Eloquent\Model as Model;
	use Illuminate\Database\Capsule\Manager as Capsule;

	$capsule = new Capsule;

	$capsule->setAsGlobal();
	$capsule->bootEloquent();
	$capsule->addConnection([
		'driver'    => 'mysql',
		'host'      =>	$database_url,
		'database'  =>  $database_name,
		'username'  =>  $database_user,
		'password'  =>  $database_pw,
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => '',
	]);

	class DamageEvent {

		public $amount;
		public $source;
		public $element;

		public function __construct($amount, $source = null, $element = null) {
			$this->amount = abs($amount);
			$this->source = $source;
			$this->element = $element;
		}

	}

	interface Target {
   		
		public function damage(DamageEvent $event);
   		
		public function heal($amount);
   		
		public function revive(Participant $by = null);
   		
		public function addEffect($effect);
	
	}

	class BaseModel extends Model {

   		public $timestamps = false;
		
		public static $functions = [];

		public function can($what) {
			return array_key_exists($this->table.':'.$what, static::$functions)
						&& array_key_exists($this->id, static::$functions[$this->table.':'.$what]);
		}

		public function __call($method, $args) {

			if(array_key_exists($this->table.':'.$method, static::$functions))
				if(array_key_exists($this->id, static::$functions[$this->table.':'.$method])) {
					$func = static::$functions[$this->table.':'.$method][$this->id];
					array_unshift($args, $this);
					return call_user_func_array($func, $args);
				}
				else return false;

			return parent::__call($method, $args);

		}

		public static function register($request, $functions = []) {

			$update = true;
			
			$id = $request['id'];
			$model = static::find($id) ?? new static;
			
			if($update) {
				foreach($request as $key => $param)
					$model->$key = $request[$key];
				$model->save();	
			}		
		
			foreach($functions as $key => $function) {
				if(!array_key_exists($model->table.':'.$key, static::$functions))
					static::$functions[$model->table.':'.$key] = [];
				static::$functions[$model->table.':'.$key][$id] = $function;
			}
		
		}

		public function icon() {
			return $this->table.'/'.$this->name;
		}

		public function color() {
			return false;
		}

		public function key() {
			if(array_key_exists('name', $this->attributes))
				return $this->table.'.'.$this->name;

			return null;
		}
		
		public function name() {
			
			$translation = $this->key();
			if(is_string($translation)) $translation = new Translation($translation);

			if($translation) {
				if(!translationExists($translation->key))
					$translation->key .= '.name';
				return $translation->format();
			}

			return $this->id;
			
		}
		
		public function description() {
			
			$translation = $this->key();
			if(is_string($translation)) $translation = new Translation($translation);

			if($translation) {
				$translation->key .= '.description';
				if(translationExists($translation->key))
					return $translation->format();
			}

			return false;			
			
		}

		function relatesTo($relation) {
			$from = $this->$relation()->getQuery()->getQuery()->from;
			return preg_replace('/([cC])lass/', '$1lazz', $from);
		}
		
	}

	function registerAll() {
		
		foreach(get_declared_classes() as $class){
			if(method_exists($class, 'registerAll')) {
				$class::registerAll();
			}
		}
		
	}

	foreach (glob("database/interfaces/*.php") as $filename)
	    include_once $filename;

	foreach (glob("database/models/*.php") as $filename)
	    include_once $filename;

	registerAll();

?>
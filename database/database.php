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

	class BaseModel extends Model {

   		public $timestamps = false;
		
		public static $functions = [];

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
				if(!array_key_exists($key, static::$functions))
					static::$functions[$key] = [];
				static::$functions[$key][$id] = $function;
			}
		
		}

		public function icon() {
			return $this->table.'/'.$this->name;
		}

		public function color() {
			return null;
		}
		
		public function name() {
			return format($this->table.'.'.$this->name);
		}
		
	}

	function registerAll() {
		
		foreach(get_declared_classes() as $class){
			if(method_exists($class, 'registerAll')) {
				$class::registerAll();
			}
		}
		
	}

	foreach (glob("database/models/*.php") as $filename) {
	    include_once $filename;
	}

	registerAll();

?>
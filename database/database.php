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
		
	}

	function registerAll() {
		
		foreach(get_declared_classes() as $class){
			if(method_exists($class, 'registerAll')) {
				$class::registerAll();
			}
		}
		
	}

	foreach (glob("database/*.php") as $filename) {
	    include_once $filename;
	}

	registerAll();

?>
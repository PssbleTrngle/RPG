<?php

	/*
		Handles everything related to the MySQL Database
		by using the Illuminate Database Model
		The various classes are imported at the bottom
	*/

	include_once 'options.php';

	use Illuminate\Database\Capsule\Manager as Capsule;

	$capsule = new Capsule;

	$capsule->setAsGlobal();
	$capsule->bootEloquent();

	$capsule->addConnection([
		'driver'    => $database_driver,
		'host'      =>	$database_url,
		'database'  =>  $database_name,
		'username'  =>  $database_user,
		'password'  =>  $database_pw,
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => '',
	]);

	function registerAll() {
		
		foreach(get_declared_classes() as $class){
			if(method_exists($class, 'registerAll')) {
				$class::registerAll();
			}
		}
		
	}

	foreach (glob("database/classes/*.php") as $filename)
	    include_once $filename;

	foreach (glob("database/models/*.php") as $filename)
	    include_once $filename;

	registerAll();

?>
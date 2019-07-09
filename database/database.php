<?php

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
   		
    	protected $primaryKey = 'id';
   		public $timestamps = false;
   		public $relations = [];
		
	}

	function registerAll() {
		
		foreach(get_declared_classes() as $class){
			if(method_exists($class, 'registerAll')) {
				$class::registerAll();
			}
		}
		
	}

	include_once 'item.php';
	include_once 'battle.php';
	include_once 'npc.php';
	include_once 'character.php';	
	include_once 'clazz.php';	
	include_once 'skill.php';
	include_once 'effect.php';		
	include_once 'location.php';

	registerAll();

?>
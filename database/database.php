<?php

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
		
		public function __get($varName) {
			$this->isPrivate($varName);

			return parent::__get($varName);
		}

		public function __set($varName, $value) {
			$this->isPrivate($varName);

			return parent::__set($varName, $value);
		}

		protected function isPrivate($varName) {
			if (in_array($varName, $this->privateProperties)) {
				throw new \Exception('The ' . $varName. ' property is private');
			}
		}
		
	}

	function registerAll() {
		
		foreach(get_declared_classes() as $class){
			if(method_exists($class, 'registerAll')) {
				$class::registerAll();
			}
		}
		
		Skill::registerAll();
		Slot::registerAll();
	
	}

	include_once 'battle.php';
	include_once 'character.php';		
	include_once 'clazz.php';		
	include_once 'item.php';		
	include_once 'location.php';

	registerAll();

?>
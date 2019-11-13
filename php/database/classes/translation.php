<?php

	class Translation extends BaseModel {

		protected $table = 'battle_messages';
		private static $glue = ';';

		public function __construct($key = '', $args = null) {
			$this->key = $key;
			if(is_string($args)) $args = [$args];
			if($args) $this->args = implode(static::$glue, str_replace(static::$glue, ',', $args));
		}

		public function format() {
			$args = $this->args ? explode(static::$glue, $this->args) : [];
			return format($this->key, $args);
		}

	}

?>
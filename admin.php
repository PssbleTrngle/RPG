<?php

	function gitPull() {

		return exec('git pull');

	}

	registerAction('/admin/update', function($args) {

		$msg = gitPull();
		return ['success' => $msg !== false, 'message' => $msg];

	}, 'admin');

?>
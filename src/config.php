<?php 

	/*
		The config file
		Defines the database name and user.
	*/

	$database_url = getenv('DB_HOST') || "localhost";
	$database_pw = getenv('DB_PASSWORD');
	$database_name = getenv('DB_NAME') || "rpg";
	$database_user = getenv('DB_USER') || $database_name;

	/* If defined everybody will use this account instead of logging in */
	$static_account = intval(getenv('RPG_STATIC_ACCOUNT'));

	/* prefer svg over png icons */
	$prefer_svg = getenv('RPG_USE_SVG') == 'true';

?>
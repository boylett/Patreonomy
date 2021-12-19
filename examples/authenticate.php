<?php

	\header("Content-Type: application/json");

	require __DIR__ . "/../vendor/autoload.php";

	$patreon = new \Patreonomy\Patreonomy();
	$config  = include __DIR__ . "/config.php";

	$patreon->connect(
		client_id:      $config["client_id"],
		client_secret:  $config["client_secret"],
		access_token:   $config["access_token"],
		refresh_token:  $config["refresh_token"],
		webhook_secret: $config["webhook_secret"],
	);

	\var_dump($patreon->getIdentity());

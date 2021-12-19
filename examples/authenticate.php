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

	$campaigns = $patreon->getCampaigns();

	$campaign = \reset($campaigns);

	\var_dump(
		$campaign->searchMembers(
			patron_status: "active_patron",
		)
	);

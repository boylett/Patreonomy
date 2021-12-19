<?php

	namespace Patreonomy\Resource;

	final class OAuthClient extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "author_name", "client_secret", "default_scopes", "description", "domain", "icon_url", "name", "privacy_policy_url", "redirect_uris", "tos_url", "version" ];

		/**
		 * The author name provided during client setup. Can be null.
		 * @var string
		 */
		public NULL|string $author_name = "";
		
		/**
		 * The client's secret.
		 * @var string
		 */
		public NULL|string $client_secret = "";
		
		/**
		 * (Deprecated in APIv2) The client's default OAuth scopes for the authorization flow.
		 * @var string
		 */
		public NULL|string $default_scopes = "";
		
		/**
		 * The description provided during client setup.
		 * @var string
		 */
		public NULL|string $description = "";
		
		/**
		 * The domain provided during client setup. Can be null.
		 * @var string
		 */
		public NULL|string $domain = "";
		
		/**
		 * The URL of the icon used in the OAuth authorization flow. Can be null.
		 * @var string
		 */
		public NULL|string $icon_url = "";
		
		/**
		 * The name provided during client setup.
		 * @var string
		 */
		public NULL|string $name = "";
		
		/**
		 * The URL of the privacy policy provided during client setup. Can be null.
		 * @var string
		 */
		public NULL|string $privacy_policy_url = "";
		
		/**
		 * The allowable redirect URIs for the OAuth authorization flow.
		 * @var string
		 */
		public NULL|string $redirect_uris = "";
		
		/**
		 * The URL of the terms of service provided during client setup. Can be null.
		 * @var string
		 */
		public NULL|string $tos_url = "";
		
		/**
		 * The Patreon API version the client is targeting.
		 * @var int
		 */
		public NULL|int $version = 0;
		
		/**
		 * (Alpha) The apps that this client controls.
		 * @var array
		 */
		public NULL|array $apps = [];
		
		/**
		 * The campaign of the user who created the OAuth Client.
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
		
		/**
		 * The token of the user who created the client.
		 * @var \Patreonomy\Resource\OAuthToken
		 */
		public NULL|\Patreonomy\Resource\OAuthToken $creator_token = NULL;
		
		/**
		 * The user who created the OAuth Client.
		 * @var \Patreonomy\Resource\User
		 */
		public NULL|\Patreonomy\Resource\User $user = NULL;
	}

<?php

	namespace Patreonomy;

	final class Patreonomy {
		/**
		 * This SDK's version number
		 * @var string
		 */
		const VERSION = "1.0.1";

		/**
		 * Patreon legacy API endpoint
		 * @var string
		 */
		const ENDPOINT_LEGACY = "https://www.patreon.com/api";

		/**
		 * Patreon API endpoint
		 * @var string
		 */
		const ENDPOINT_API = "https://www.patreon.com/api/oauth2/v2";

		/**
		 * Patreon OAuth endpoint
		 * @var string
		 */
		const ENDPOINT_OAUTH = "https://www.patreon.com/oauth2";

		/**
		 * Authorization storage
		 * @var array
		 */
		protected array $authorization = [];

		/**
		 * List of available campaign categories
		 * @var array
		 */
		public array $categories = [];

		/**
		 * List of available campaigns
		 * @var array
		 */
		public array $campaigns = [];

		/**
		 * List of available webhooks
		 * @var array
		 */
		public array $webhooks = [];

		/**
		 * Adopt a resource
		 * @param  \Patreonomy\Resource\AbstractResource $resource Resource
		 * @return \Patreonomy\Resource\AbstractResource           Resource
		 */
		public function adopt(\Patreonomy\Resource\AbstractResource $resource) : \Patreonomy\Resource\AbstractResource {
			$resource->__parent = $this;

			return $resource;
		}

		/**
		 * Connect Patreonerly to the Patreon API
		 * @param string $client_id     Used to identify your application/tool with the client you registered
		 * @param string $client_secret Used to authenticate your application/tool with the client you registered
		 * @param string $access_token  Which can be used to access the API in the context of the creator you account you made when registering a client
		 * @param string $refresh_token Can be used to refresh new access tokens
		 */
		public function connect(...$arguments) : self {
			$this->authorization = $arguments;

			return $this;
		}

		/**
		 * Create a new webhook (UNTESTED AND UNDOCUMENTED)
		 * @param  int                          $campaign_id The webhook's main campaign ID
		 * @param  string                       $uri         The webhook's destination URI
		 * @param  array                        $triggers    Array of webhook triggers (see constants in \Patreonomy\Resource\Webhook)
		 * @return \Patreonomy\Resource\Webhook              The resulting Webhook object
		 */
		public function createWebhook(...$arguments) : \Patreonomy\Resource\Webhook {
			$campaign_id ??= 0;
			$uri         ??= "";

			$triggers ??= [
				\Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_CREATE,
				\Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_UPDATE,
				\Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_DELETE,
				\Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_PLEDGE_CREATE,
				\Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_PLEDGE_UPDATE,
				\Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_PLEDGE_DELETE,
				\Patreonomy\Resource\Webhook::TRIGGER_POSTS_PUBLISH,
				\Patreonomy\Resource\Webhook::TRIGGER_POSTS_UPDATE,
				\Patreonomy\Resource\Webhook::TRIGGER_POSTS_DELETE,
			];

			if (!$campaign_id) {
				throw new \Patreonomy\Response\Exception("You must supply a Campaign ID (campaign_id) when using " . \get_called_class() . "::createWebhook()");
			}

			if (!$uri) {
				throw new \Patreonomy\Response\Exception("You must supply a Webhook Destination URI (uri) when using " . \get_called_class() . "::createWebhook()");
			}

			$response = $this->request(
				endpoint: static::ENDPOINT_API . "/webhooks",
				type:     "POST",
				data:     [
					"data" => [
						"type"          => "webhook",
						"attributes"    => [
							"triggers" => $triggers,
							"uri"      => $uri,
						],
						"relationships" => [
							"campaign" => [
								"data" => [
									"type" => "campaign",
									"id"   => $campaign_id,
								],
							],
						],
					],
				],
			);

			return $this->Webhook($response["data"]["id"])->__populate($response);
		}

		/**
		 * Make a request to the Patreon API
		 * @param  bool   $authorization Whether to send an OAuth Bearer Token header
		 * @param  array  $data          Payload data
		 * @param  string $endpoint      Relative path to Patreon API endpoint
		 * @param  string $type          HTTP request type - one of "get", "patch" or "post"
		 * @return array                 JSON response
		 */
		public function request(...$arguments) : array {
			\extract($arguments);

			$authorization ??= true;
			$data          ??= [];
			$form_params   ??= [];
			$options       ??= [];
			$response        = [];
			$type            = \strtoupper($type ?? "get");

			if (!isset($endpoint)) {
				throw new \Patreonomy\Response\Exception("You must supply an Endpoint (endpoint) when using " . \get_called_class() . "::request()");
			}

			if (\str_contains($endpoint, ":")) {
				$endpoint = \preg_replace_callback_array([
					"/:([^:\/$]+)/" => function ($matches) use (&$data) {
						if (isset($data[$matches[1]])) {
							$value = $data[$matches[1]];

							unset($data[$matches[1]]);

							return $value;
						}
						
						throw new \Patreonomy\Response\Exception("Endpoint variable " . $matches[0] . " not supplied in " . \get_called_class() . "::request()");
					},
				], $endpoint);
			}

			if (!in_array($type, [ "GET", "PATCH", "POST" ])) {
				throw new \Patreonomy\Response\Exception("Unsupported HTTP request type '" . $type . "'");
			}

			if (!empty($data ?? []) and ($type === "GET")) {
				$endpoint .= \str_contains($endpoint, "?") ? "&" : "?";
				$endpoint .= \http_build_query($data);

				unset($data);
			}

			else if (!empty($data)) {
				$options["json"] = $data;
			}

			if (!empty($form_params)) {
				$options["form_params"] = $form_params;
			}

			if ($authorization) {
				if (!isset($this->authorization["access_token"])) {
					throw new \Patreonomy\Response\Exception("You must supply an Access Token (access_token) when using " . \get_called_class() . "::connect()");
				}

				$options = [
					"headers" => [
						"Authorization" => "Bearer " . $this->authorization["access_token"],
					],
				];
			}

			$options["headers"]["User-Agent"] = "Patreonomy, version " . static::VERSION . ", platform " . php_uname("s") . "-" . php_uname("r");

			$guzzle  = new \GuzzleHttp\Client([ "http_errors" => false ]);
			$request = $guzzle->request($type, $endpoint, $options);

			$json = @\json_decode(\trim($request->getBody()) ?: "[]", true);

			if (\json_last_error() === JSON_ERROR_NONE) {
				if (isset($json["error"])) {
					throw new \Patreonomy\Response\Exception(\ucwords($type) . " failed for '" . $endpoint . "' with HTTP " . $request->getStatusCode() . " in " . \get_called_class() . ":\n" . $json["error"]);
				}

				else if (isset($json["errors"])) {
					throw new \Patreonomy\Response\Exception(\ucwords($type) . " failed for '" . $endpoint . "' with HTTP " . $request->getStatusCode() . " in " . \get_called_class() . ":\n" . \print_r($json["errors"], true));
				}

				return $json;
			}

			$error_code = __CLASS__ . "::ERROR_" . $request->getStatusCode();

			throw new \Patreonomy\Response\Exception(
				\ucwords($type) . " failed for '" . $endpoint . "' with HTTP " . $request->getStatusCode() . " in " . \get_called_class() . ":\n" .
				(\defined($error_code) ? \constant($error_code) . "\n" : "") .
				$request->getBody());

			return [];
		}

		/**
		 * Get a list of campaigns that this token has access to
		 * @param  array $fields   Array of field flags
		 * @param  array $includes Array of include flags
		 * @return array           Array of Campaign objects
		 */
		public function getCampaigns(...$arguments) : array {
			if (empty($this->campaigns)) {
				\extract($arguments);

				$fields ??= [
					"benefit"  => \Patreonomy\Resource\Benefit::ALL_FIELD_FLAGS,
					"campaign" => \Patreonomy\Resource\Campaign::ALL_FIELD_FLAGS,
					"goal"     => \Patreonomy\Resource\Goal::ALL_FIELD_FLAGS,
					"tier"     => \Patreonomy\Resource\Tier::ALL_FIELD_FLAGS,
					"user"     => \Patreonomy\Resource\User::ALL_FIELD_FLAGS,
				];

				$includes ??= [
					"benefits",
					"categories",
					"creator",
					"goals",
					"tiers",

					// TODO: Figure out what this is for
					//"campaign_installations",
				];
				
				$this->campaigns = $this->getResources(
					resource: "Campaign",
					endpoint: static::ENDPOINT_API . "/campaigns",
					fields:   $fields,
					includes: $includes,
				);
			}

			return $this->campaigns;
		}

		/**
		 * Get a list of all available campaign categories
		 * @return array Array of Category objects
		 */
		public function getCategories() : array {
			if (empty($this->categories)) {
				$this->categories = $this->getResources(
					resource: "Category",
					endpoint: static::ENDPOINT_LEGACY . "/categories",
				);
			}

			return $this->categories;
		}

		/**
		 * Access information about the current User with reference to the oauth token
		 * @return \Patreonomy\Resource\User
		 */
		public function getIdentity() : \Patreonomy\Resource\User {
			if (empty($this->identity)) {
				$response = $this->request(
					endpoint: static::ENDPOINT_API . "/identity",
					type:     "GET",
					data:     \Patreonomy\Resource\AbstractResource::buildFields([
						"user" => \Patreonomy\Resource\User::ALL_FIELD_FLAGS,
					], [
						"memberships",
					]),
				);

				$user = $this->adopt(new \Patreonomy\Resource\User());

				$this->identity = $user->__populate($response);
			}

			return $this->identity;
		}

		/**
		 * Get the OAuth2 authorization request URL
		 * @param  array|string $scope         An array or space-separated list of OAuth2 scopes (see constants in \Patreonomy\Response\OAuthToken)
		 * @param  string       $prompt        Controls how the authorization flow handles existing authorizations. Can be set to 'none' or 'consent' - defaults to 'consent'
		 * @param  string       $redirect_uri  Whatever URL you registered when creating your application
		 * @param  string       $response_type Whether to request an implicit grant ('token') or explicit grant ('code') - defaults to 'code'
		 * @param  string       $state         Unique verification string or hash to verify connection origin
		 * @return string                      OAuth2 authorization URL
		 */
		public function getOAuthUrl(...$arguments) : string {
			if (!isset($this->authorization["client_id"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Client/Application ID (client_id) during " . \get_called_class() . "::connect() when using " . \get_called_class() . "::getOAuthUrl()");
			}

			$arguments["response_type"] ??= "code";

			if (($arguments["response_type"] === "token") and !isset($this->authorization["client_secret"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Client Secret (client_secret) during " . \get_called_class() . "::connect() when using " . \get_called_class() . "::getOAuthUrl(response_type: 'token')");
			}

			if (!isset($arguments["redirect_uri"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Redirect URI (redirect_uri) when using " . \get_called_class() . "::getOAuthUrl()");
			}

			$default_scope = \implode(" ", [
				\Patreonomy\Resource\OAuthToken::SCOPE_IDENTITY,
				\Patreonomy\Resource\OAuthToken::SCOPE_IDENTITY_MEMBERSHIPS,
				\Patreonomy\Resource\OAuthToken::SCOPE_CAMPAIGNS,
			]);
			
			if (empty($arguments["scope"] ?? "")) {
				$arguments["scope"] = $default_scope;
			}

			else if (\is_array($arguments["scope"] ?? "")) {
				$arguments["scope"] = \implode(" ", \array_unique(\array_merge($arguments["scope"]), \explode(" ", $default_scope)));
			}

			if (!isset($arguments["state"])) {
				$left     = \hash("CRC32B", __FILE__ . \random_bytes(8));
				$time_max = \strtotime("+1 hour");
				$right    = \hash("CRC32B", $left . "Patreonomy OAuth State Hash" . $time_max);

				$arguments["state"] = $left . $time_max . $right;
			}

			return static::ENDPOINT_OAUTH . "/authorize?" . \http_build_query(\array_merge($arguments, [
				"client_id"     => $this->authorization["client_id"],
			], ($arguments["response_type"] === "token") ? [] : [
				"client_secret" => $this->authorization["client_secret"],
			]));
		}

		/**
		 * Get a set of resources attached to this resource
		 * @param  array ...$arguments Arguments
		 * @return array               Array of resources
		 */
		public function getResources(...$arguments) : array {
			\extract($arguments);

			/**
			 * Item limit is documented as page[count] but is actually page[size]
			 * @link https://docs.patreon.com/#pagination-and-sorting                                     (INCORRECT)
			 * @link https://www.patreondevelopers.com/t/page-count-query-parameter-is-not-working/1717/4 (CORRECT)
			 */

			/**
			 * page[size] maximum is 200
			 * @link https://www.patreondevelopers.com/t/recommended-api-usage-limits/122/5
			 */

			$autofetch ??= true;
			$count     ??= 200;
			$cursor    ??= false;
			$endpoint  ??= false;
			$fields    ??= [];
			$includes  ??= [];
			$resource  ??= false;
			$results     = [];
			$sort      ??= false;

			if (!$endpoint) {
				throw new \Patreonomy\Response\Exception("No endpoint supplied in " . \get_called_class() . "::getResources()");
			}

			if (!$resource or !\method_exists($this, $resource)) {
				throw new \Patreonomy\Response\Exception("Resource type '" . $resource . "' does not exist");
			}

			$response = $this->request(
				endpoint: $endpoint,
				type:     "GET",
				data:     \array_merge(
					\Patreonomy\Resource\AbstractResource::buildFields($fields, $includes),
					$count  ? [ "page[size]"   => $count  ] : [],
					$cursor ? [ "page[cursor]" => $cursor ] : [],
					$sort   ? [ "page[sort]"   => $sort   ] : [],
				),
			);

			foreach ($response["data"] ?? [] as $data) {
				$results[] = $this->{$resource}($data["id"])->__populate([
					"data"     => $data,
					"included" => $response["included"] ?? [],
				]);
			}

			if ($autofetch and $response["meta"]["pagination"]["cursors"]["next"] ?? false) {
				$results = \array_merge($results, \call_user_func_array([ $this, "getResources" ], \array_merge($arguments, [
					"cursor" => $response["meta"]["pagination"]["cursors"]["next"],
				])));
			}

			return $results;
		}

		/**
		 * Get the Webhooks for the current user's Campaign created by the API client. You will only be able to see webhooks created by your client. Requires the w:campaigns.webhook scope.
		 * TODO: Figure out what's going on here. This endpoint doesn't seem to work yet.
		 * @param  array $fields   Array of field flags
		 * @param  array $includes Array of include flags
		 * @return array           Array of Webhook objects
		 */
		public function getWebhooks(...$arguments) : array {
			if (empty($this->webhooks)) {
				\extract($arguments);

				$fields ??= [
					"webhook"  => \Patreonomy\Resource\Webhook::ALL_FIELD_FLAGS,
					"campaign" => \Patreonomy\Resource\Campaign::ALL_FIELD_FLAGS,
					
					// TODO: For some reason, including the 'client' field crashes this endpoint. Must investigate.
					//"client"   => \Patreonomy\Resource\OAuthClient::ALL_FIELD_FLAGS,
				];

				$includes ??= [
					"campaign",
					"client",
				];
				
				$this->webhooks = $this->getResources(
					resource: "Webhook",
					endpoint: static::ENDPOINT_API . "/webhooks",
					fields:   $fields,
					includes: $includes,
				);
			}

			return $this->webhooks;
		}

		/**
		 * Handle a Webhook interaction
		 * @param  callable $callback Callback method that handles the interaction. Arguments: (array $payload, array $headers, string $trigger)
		 * @return self
		 */
		public function receiveWebhook(callable $callback) : self {
			if (!isset($this->authorization["webhook_secret"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Webhook Secret (webhook_secret) during " . \get_called_class() . "::connect() when using " . \get_called_class() . "::receiveWebhook()");
			}

			$raw_data = \file_get_contents("php://input") ?: "{}";
			$data     = \json_decode($raw_data, true);

			/**
			 * Verify the payload with hash_hmac
			 */
			if (\hash_equals($_SERVER["HTTP_X_PATREON_SIGNATURE"] ?? "", \hash_hmac("md5", $raw_data, $this->authorization["webhook_secret"]))) {
				/**
				 * Determine what to do based on the patreon event header
				 */
				switch ($_SERVER["HTTP_X_PATREON_EVENT"] ?? "") {
					default: {
						throw new \Patreonomy\Response\Exception("Unknown interaction type");
					}

					break;

					case \Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_CREATE:
					case \Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_UPDATE:
					case \Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_DELETE:
					case \Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_PLEDGE_CREATE:
					case \Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_PLEDGE_UPDATE:
					case \Patreonomy\Resource\Webhook::TRIGGER_MEMBERS_PLEDGE_DELETE:
					case \Patreonomy\Resource\Webhook::TRIGGER_POSTS_PUBLISH:
					case \Patreonomy\Resource\Webhook::TRIGGER_POSTS_UPDATE:
					case \Patreonomy\Resource\Webhook::TRIGGER_POSTS_DELETE: {
						\call_user_func_array($callback, [
							"payload" => $data,
							"headers" => \array_filter($_SERVER, fn($value, $key) => \preg_match("/^HTTPS?_/i", $key), ARRAY_FILTER_USE_BOTH),
							"trigger" => $_SERVER["HTTP_X_PATREON_EVENT"],
						]);
					}
				}
			}

			else {
				throw new \Patreonomy\Response\Exception("Signature verification failed");
			}

			return $this;
		}

		/**
		 * Refresh an OAuth2 token with Patreon
		 * @param  string                          $refresh_token Refresh token
		 * @return \Patreonomy\Resource\OAuthToken
		 */
		public function refreshOAuthToken(string $refresh_token) : \Patreonomy\Resource\OAuthToken {
			if (!isset($this->authorization["client_id"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Client ID (client_id) during " . \get_called_class() . "::connect() when using " . \get_called_class() . "::refreshOAuthToken()");
			}

			if (!isset($this->authorization["client_secret"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Client Secret (client_secret) during " . \get_called_class() . "::connect() when using " . \get_called_class() . "::refreshOAuthToken()");
			}

			$token = $this->request(
				authorization: false,
				endpoint:      static::ENDPOINT_LEGACY . "/oauth2/token",
				type:          "POST",
				form_params:   [
					"client_id"     => $this->authorization["client_id"],
					"client_secret" => $this->authorization["client_secret"],
					"grant_type"    => "refresh_token",
					"refresh_token" => $refresh_token,
				],
			);

			return $this->adopt(new \Patreonomy\Resource\OAuthToken())->__populate([
				"data" => [
					"attributes" => $token,
				],
			]);
		}

		/**
		 * Request an OAuth2 token from Patreon
		 * @param  string                            $redirect_uri Your redirect URI
		 * @param  string                            $code         The code from the querystring
		 * @return \Patreonomy\Resource\OAuthToken
		 */
		public function requestOAuthToken(string $redirect_uri, string $code = "") : \Patreonomy\Resource\OAuthToken {
			if (!isset($this->authorization["client_id"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Client ID (client_id) during " . \get_called_class() . "::connect() when using " . \get_called_class() . "::requestOAuthToken()");
			}

			if (!isset($this->authorization["client_secret"])) {
				throw new \Patreonomy\Response\Exception("You must supply a Client Secret (client_secret) during " . \get_called_class() . "::connect() when using " . \get_called_class() . "::requestOAuthToken()");
			}

			$token = $this->request(
				authorization: false,
				endpoint:      static::ENDPOINT_LEGACY . "/oauth2/token",
				type:          "POST",
				form_params:   [
					"client_id"     => $this->authorization["client_id"],
					"client_secret" => $this->authorization["client_secret"],
					"code"          => $code ?: ($_GET["code"] ?? ""),
					"grant_type"    => "authorization_code",
					"redirect_uri"  => $redirect_uri,
				],
			);

			return $this->adopt(new \Patreonomy\Resource\OAuthToken())->__populate([
				"data" => [
					"attributes" => $token,
				],
			]);
		}

		/**
		 * Verify the supplied OAuth State Hash
		 * @param  string $state State hash
		 * @return bool          Whether the state hash is valid
		 */
		public function verifyOAuthState(string $state) : bool {
			if (\preg_match("/^([a-f0-9]{8})([0-9]+)([a-f0-9]{8})$/i", $state, $parts)) {
				$left     = $parts[1] ?? "";
				$time_max = \intval($parts[2] ?? 0);
				$right    = $parts[3] ?? "";
				$hash     = \hash("CRC32B", $left . "Patreonomy OAuth State Hash" . $time_max);

				if ($hash === $right and $time_max > \time()) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Retrieve or create an Address instance
		 * @param string $id Resource ID
		 */
		public function Address(string $id) : \Patreonomy\Resource\Address {
			return $this->adopt(\Patreonomy\Resource\Address::__instance($id));
		}

		/**
		 * Retrieve or create an Attachment instance
		 * @param string $id Resource ID
		 */
		public function Attachment(string $id) : \Patreonomy\Resource\Attachment {
			return $this->adopt(\Patreonomy\Resource\Attachment::__instance($id));
		}

		/**
		 * Retrieve or create a Benefit instance
		 * @param string $id Resource ID
		 */
		public function Benefit(string $id) : \Patreonomy\Resource\Benefit {
			return $this->adopt(\Patreonomy\Resource\Benefit::__instance($id));
		}

		/**
		 * Retrieve or create a Campaign instance
		 * @param string $id Resource ID
		 */
		public function Campaign(string $id) : \Patreonomy\Resource\Campaign {
			return $this->adopt(\Patreonomy\Resource\Campaign::__instance($id));
		}

		/**
		 * Retrieve or create a CampaignInstallation instance
		 * @param string $id Resource ID
		 */
		public function CampaignInstallation(string $id) : \Patreonomy\Resource\CampaignInstallation {
			return $this->adopt(\Patreonomy\Resource\CampaignInstallation::__instance($id));
		}

		/**
		 * Retrieve or create a Category instance
		 * @param string $id Resource ID
		 */
		public function Category(string $id) : \Patreonomy\Resource\Category {
			return $this->adopt(\Patreonomy\Resource\Category::__instance($id));
		}

		/**
		 * Retrieve or create a Comment instance
		 * @param string $id Resource ID
		 */
		public function Comment(string $id) : \Patreonomy\Resource\Comment {
			return $this->adopt(\Patreonomy\Resource\Comment::__instance($id));
		}

		/**
		 * Retrieve or create a Deliverable instance
		 * @param string $id Resource ID
		 */
		public function Deliverable(string $id) : \Patreonomy\Resource\Deliverable {
			return $this->adopt(\Patreonomy\Resource\Deliverable::__instance($id));
		}

		/**
		 * Retrieve or create a Goal instance
		 * @param string $id Resource ID
		 */
		public function Goal(string $id) : \Patreonomy\Resource\Goal {
			return $this->adopt(\Patreonomy\Resource\Goal::__instance($id));
		}

		/**
		 * Retrieve or create a Media instance
		 * @param string $id Resource ID
		 */
		public function Media(string $id) : \Patreonomy\Resource\Media {
			return $this->adopt(\Patreonomy\Resource\Media::__instance($id));
		}

		/**
		 * Retrieve or create a Member instance
		 * @param string $id Resource ID
		 */
		public function Member(string $id) : \Patreonomy\Resource\Member {
			return $this->adopt(\Patreonomy\Resource\Member::__instance($id));
		}

		/**
		 * Retrieve or create an OAuthClient instance
		 * @param string $id Resource ID
		 */
		public function OAuthClient(string $id) : \Patreonomy\Resource\OAuthClient {
			return $this->adopt(\Patreonomy\Resource\OAuthClient::__instance($id));
		}

		/**
		 * Retrieve or create an OAuthToken instance
		 * @param string $id Resource ID
		 */
		public function OAuthToken(string $id) : \Patreonomy\Resource\OAuthToken {
			return $this->adopt(\Patreonomy\Resource\OAuthToken::__instance($id));
		}

		/**
		 * Retrieve or create a PledgeEvent instance
		 * @param string $id Resource ID
		 */
		public function PledgeEvent(string $id) : \Patreonomy\Resource\PledgeEvent {
			return $this->adopt(\Patreonomy\Resource\PledgeEvent::__instance($id));
		}

		/**
		 * Retrieve or create a Post instance
		 * @param string $id Resource ID
		 */
		public function Post(string $id) : \Patreonomy\Resource\Post {
			return $this->adopt(\Patreonomy\Resource\Post::__instance($id));
		}

		/**
		 * Retrieve or create a PostTag instance
		 * @param string $id Resource ID
		 */
		public function PostTag(string $id) : \Patreonomy\Resource\PostTag {
			return $this->adopt(\Patreonomy\Resource\PostTag::__instance($id));
		}

		/**
		 * Retrieve or create a Tier instance
		 * @param string $id Resource ID
		 */
		public function Tier(string $id) : \Patreonomy\Resource\Tier {
			return $this->adopt(\Patreonomy\Resource\Tier::__instance($id));
		}

		/**
		 * Retrieve or create a User instance
		 * @param string $id Resource ID
		 */
		public function User(string $id) : \Patreonomy\Resource\User {
			return $this->adopt(\Patreonomy\Resource\User::__instance($id));
		}

		/**
		 * Retrieve or create a Webhook instance
		 * @param string $id Resource ID
		 */
		public function Webhook(string $id) : \Patreonomy\Resource\Webhook {
			return $this->adopt(\Patreonomy\Resource\Webhook::__instance($id));
		}
	}

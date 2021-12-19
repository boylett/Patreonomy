<?php

	namespace Patreonomy\Resource;

	final class OAuthToken extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Provides read access to data about the user. See the /identity endpoint documentation for details about what data is available.
		 * @var string
		 */
		const SCOPE_IDENTITY = "identity";
		
		/**
		 * Provides read access to the user’s email.
		 * @var string
		 */
		const SCOPE_IDENTITY_EMAIL = "identity[email]";
		
		/**
		 * Provides read access to the user’s memberships.
		 * @var string
		 */
		const SCOPE_IDENTITY_MEMBERSHIPS = "identity.memberships";
		
		/**
		 * Provides read access to basic campaign data. See the /campaign endpoint documentation for details about what data is available.
		 * @var string
		 */
		const SCOPE_CAMPAIGNS = "campaigns";
		
		/**
		 * Provides read, write, update, and delete access to the campaign’s webhooks created by the client.
		 * @var string
		 */
		const SCOPE_CAMPAIGNS_WEBHOOK = "w:campaigns.webhook";
		
		/**
		 * Provides read access to data about a campaign’s members. See the /members endpoint documentation for details about what data is available. Also allows the same information to be sent via webhooks created by your client.
		 * @var string
		 */
		const SCOPE_CAMPAIGNS_MEMBERS = "campaigns.members";
		
		/**
		 * Provides read access to the member’s email. Also allows the same information to be sent via webhooks created by your client.
		 * @var string
		 */
		const SCOPE_CAMPAIGNS_MEMBERS_EMAIL = "campaigns.members[email]";
		
		/**
		 * Provides read access to the member’s address, if an address was collected in the pledge flow. Also allows the same information to be sent via webhooks created by your client.
		 * @var string
		 */
		const SCOPE_CAMPAIGNS_MEMBERS_ADDRESS = "campaigns.members.address";
		
		/**
		 * Provides read access to the posts on a campaign.
		 * @var string
		 */
		const SCOPE_CAMPAIGNS_POSTS = "campaigns.posts";

		/**
		 * Single use token
		 * @var string
		 */
		public NULL|string $access_token = "";
		
		/**
		 * Single use token
		 * @var string
		 */
		public NULL|string $refresh_token = "";
		
		/**
		 * Token lifetime duration
		 * @var int
		 */
		public NULL|int $expires_in = 0;
		
		/**
		 * Token scopes
		 * @var string
		 */
		public NULL|string $scope = "";
		
		/**
		 * Token type
		 * @var string
		 */
		public NULL|string $token_type = "Bearer";
		
		/**
		 * API Version
		 * @var string
		 */
		public NULL|string $version = "0.0.0";
	}

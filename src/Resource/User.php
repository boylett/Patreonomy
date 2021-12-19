<?php

	namespace Patreonomy\Resource;

	final class User extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "about", "can_see_nsfw", "created", "email", "first_name", "full_name", "hide_pledges", "image_url", "is_email_verified", "last_name", "like_count", "social_connections", "thumb_url", "url", "vanity" ];

		/**
		 * The user's about text, which appears on their profile. Can be null.
		 * @var string
		 */
		public NULL|string $about = "";
		
		/**
		 * true if this user can view nsfw content. Can be null.
		 * @var bool
		 */
		public NULL|bool $can_see_nsfw = false;
		
		/**
		 * (UTC ISO format)	Datetime of this user's account creation.
		 * @var string
		 */
		public NULL|string $created = "";
		
		/**
		 * The user's email address. Requires certain scopes to access. See the scopes section of this documentation.
		 * @var string
		 */
		public NULL|string $email = "";
		
		/**
		 * First name. Can be null.
		 * @var string
		 */
		public NULL|string $first_name = "";
		
		/**
		 * Combined first and last name.
		 * @var string
		 */
		public NULL|string $full_name = "";
		
		/**
		 * true if the user has chosen to keep private which creators they pledge to. Can be null.
		 * @var bool
		 */
		public NULL|bool $hide_pledges = false;
		
		/**
		 * The user's profile picture URL, scaled to width 400px.
		 * @var string
		 */
		public NULL|string $image_url = "";
		
		/**
		 * true if the user has confirmed their email.
		 * @var bool
		 */
		public NULL|bool $is_email_verified = false;
		
		/**
		 * Last name. Can be null.
		 * @var string
		 */
		public NULL|string $last_name = "";
		
		/**
		 * How many posts this user has liked.
		 * @var int
		 */
		public NULL|int $like_count = 0;
		
		/**
		 * Mapping from user's connected app names to external user id on the respective app.
		 * @var array
		 */
		public NULL|array $social_connections = [];
		
		/**
		 * The user's profile picture URL, scaled to a square of size 100x100px.
		 * @var string
		 */
		public NULL|string $thumb_url = "";
		
		/**
		 * URL of this user's creator or patron profile.
		 * @var string
		 */
		public NULL|string $url = "";
		
		/**
		 * The public "username" of the user. patreon.com/ goes to this user's creator page. Non-creator users might not have a vanity. [Deprecated! use campaign.vanity] Can be null.
		 * @var string
		 */
		public NULL|string $vanity = "";
		
		/**
		 * The user's campaign
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
		
		/**
		 * Usually a zero or one-element array with the user's membership to the token creator's campaign, if they are a member. With the identity.memberships scope, this returns memberships to ALL campaigns the user is a member of.
		 * @var array
		 */
		public NULL|array $memberships = [];

		/**
		 * Get the data for this resource
		 * @legacy This method uses a v1 API endpoint
         * @param  array $fields   Array of field flags
         * @param  array $includes Array of include flags
		 * @return self
		 */
		public function get(
            array $fields   = [],
            array $includes = [],
        ) : self {
			return parent::__getData(
				endpoint: \Patreonomy\Patreonomy::ENDPOINT_LEGACY . "/user/" . $this->getId(),
				fields:   $fields ?: [
					"member" => \Patreonomy\Resource\Member::ALL_FIELD_FLAGS,
					"user"   => \Patreonomy\Resource\User::ALL_FIELD_FLAGS,
				],
				includes: $includes ?: [
					"campaign",
					"memberships",
				],
			);
		}
	}

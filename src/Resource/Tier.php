<?php

	namespace Patreonomy\Resource;

	final class Tier extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "amount_cents", "created_at", "description", "discord_role_ids", "edited_at", "image_url", "patron_count", "post_count", "published", "published_at", "remaining", "requires_shipping", "title", "unpublished_at", "url", "user_limit" ];

		/**
		 * Monetary amount associated with this tier (in U.S. cents).
		 * @var int
		 */
		public NULL|int $amount = 0;
		
		/**
		 * Monetary amount associated with this tier (in U.S. cents).
		 * @var int
		 */
		public NULL|int $amount_cents = 0;
		
		/**
		 * (UTC ISO format)	Datetime this tier was created.
		 * @var string
		 */
		public NULL|string $created_at = "";
		
		/**
		 * Tier currency.
		 * @var string
		 */
		public NULL|string $currency = "";
		
		/**
		 * Tier display description.
		 * @var string
		 */
		public NULL|string $description = "";
		
		/**
		 * The discord role IDs granted by this tier. Can be null.
		 * @var array
		 */
		public NULL|array $discord_role_ids = [];
		
		/**
		 * (UTC ISO format)	Datetime tier was last modified.
		 * @var string
		 */
		public NULL|string $edited_at = "";
		
		/**
		 * Full qualified image URL associated with this tier. Can be null.
		 * @var string
		 */
		public NULL|string $image_url = "";
		
		/**
		 * Amount that the patron paid.
		 * @var int
		 */
		public NULL|int $patron_amount_cents = 0;
		
		/**
		 * Number of patrons currently registered for this tier.
		 * @var int
		 */
		public NULL|int $patron_count = 0;
		
		/**
		 * Number of posts published to this tier. Can be null.
		 * @var int
		 */
		public NULL|int $post_count = 0;
		
		/**
		 * true if the tier is currently published.
		 * @var bool
		 */
		public NULL|bool $published = false;
		
		/**
		 * (UTC ISO format)	Datetime this tier was last published. Can be null.
		 * @var string
		 */
		public NULL|string $published_at = "";
		
		/**
		 * Remaining number of patrons who may subscribe, if there is a user_limit. Can be null.
		 * @var int
		 */
		public NULL|int $remaining = 0;
		
		/**
		 * true if this tier requires a shipping address from patrons.
		 * @var bool
		 */
		public NULL|bool $requires_shipping = false;
		
		/**
		 * Tier display title.
		 * @var string
		 */
		public NULL|string $title = "";
		
		/**
		 * (UTC ISO format)	Datetime tier was unpublished, while applicable. Can be null.
		 * @var string
		 */
		public NULL|string $unpublished_at = "";
		
		/**
		 * Fully qualified URL associated with this tier.
		 * @var string
		 */
		public NULL|string $url = "";
		
		/**
		 * Maximum number of patrons this tier is limited to, if applicable. Can be null.
		 * @var int
		 */
		public NULL|int $user_limit = 0;
		
		/**
		 * The benefits attached to the tier, which are used for generating deliverables.
		 * @var array
		 */
		public NULL|array $benefits = [];
		
		/**
		 * The campaign the tier belongs to.
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
		
		/**
		 * The image file associated with the tier.
		 * @var \Patreonomy\Resource\Media
		 */
		public NULL|\Patreonomy\Resource\Media $tier_image = NULL;

		/**
		 * Get the data for this resource
		 * @param  array $fields   Array of field flags
		 * @param  array $includes Array of include flags
		 * @return self
		 */
		public function get(
			array $fields   = [],
			array $includes = [],
		) : self {
			return parent::__getData(
				endpoint: \Patreonomy\Patreonomy::ENDPOINT_LEGACY . "/rewards/" . $this->getId(),
				fields:   $fields,
				includes: $includes,
			);
		}
	}

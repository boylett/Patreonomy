<?php

	namespace Patreonomy\Resource;

	final class Campaign extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "created_at", "creation_name", "discord_server_id", "google_analytics_id", "has_rss", "has_sent_rss_notify", "image_small_url", "image_url", "is_charged_immediately", "is_monthly", "is_nsfw", "main_video_embed", "main_video_url", "one_liner", "patron_count", "pay_per_name", "pledge_url", "published_at", "rss_artwork_url", "rss_feed_title", "show_earnings", "summary", "thanks_embed", "thanks_msg", "thanks_video_url", "url", "vanity" ];

		/**
		 * Datetime that the creator first began the campaign creation process. See published_at. (UTC ISO format)
		 * @var string
		 */
		public NULL|string $created_at = "";
		
		/**
		 * The type of content the creator is creating, as in "vanity is creating creation_name". Can be null.
		 * @var string
		 */
		public NULL|string $creation_name = "";
		
		/**
		 * The ID of the external discord server that is linked to this campaign. Can be null.
		 * @var string
		 */
		public NULL|string $discord_server_id = "";
		
		/**
		 * The ID of the Google Analytics tracker that the creator wants metrics to be sent to. Can be null.
		 * @var string
		 */
		public NULL|string $google_analytics_id = "";
		
		/**
		 * Whether this user has opted-in to rss feeds.
		 * @var bool
		 */
		public NULL|bool $has_rss = false;
		
		/**
		 * Whether or not the creator has sent a one-time rss notification email.
		 * @var bool
		 */
		public NULL|bool $has_sent_rss_notify = false;
		
		/**
		 * URL for the campaign's profile image.
		 * @var string
		 */
		public NULL|string $image_small_url = "";
		
		/**
		 * Banner image URL for the campaign.
		 * @var string
		 */
		public NULL|string $image_url = "";
		
		/**
		 * true if the campaign charges upfront, false otherwise. Can be null.
		 * @var bool
		 */
		public NULL|bool $is_charged_immediately = false;
		
		/**
		 * true if the campaign charges per month, false if the campaign charges per-post.
		 * @var bool
		 */
		public NULL|bool $is_monthly = false;
		
		/**
		 * true if the creator has marked the campaign as containing nsfw content.
		 * @var bool
		 */
		public NULL|bool $is_nsfw = false;
		
		/**
		 * Can be null.
		 * @var string
		 */
		public NULL|string $main_video_embed = "";
		
		/**
		 * Can be null.
		 * @var string
		 */
		public NULL|string $main_video_url = "";
		
		/**
		 * Pithy one-liner for this campaign, displayed on the creator page. Can be null.
		 * @var string
		 */
		public NULL|string $one_liner = "";
		
		/**
		 * Number of patrons pledging to this creator.
		 * @var int
		 */
		public NULL|int $patron_count = 0;
		
		/**
		 * The thing which patrons are paying per, as in "vanity is making $1000 per pay_per_name". Can be null.
		 * @var string
		 */
		public NULL|string $pay_per_name = "";
		
		/**
		 * Relative (to patreon.com) URL for the pledge checkout flow for this campaign.
		 * @var string
		 */
		public NULL|string $pledge_url = "";
		
		/**
		 * (UTC ISO format)	Datetime that the creator most recently published (made publicly visible) the campaign. Can be null.
		 * @var string
		 */
		public NULL|string $published_at = "";
		
		/**
		 * The url for the rss album artwork. Can be null.
		 * @var string
		 */
		public NULL|string $rss_artwork_url = "";
		
		/**
		 * The title of the campaigns rss feed.
		 * @var string
		 */
		public NULL|string $rss_feed_title = "";
		
		/**
		 * Whether the campaign's total earnings are shown publicly
		 * @var bool
		 */
		public NULL|bool $show_earnings = false;
		
		/**
		 * The creator's summary of their campaign. Can be null.
		 * @var string
		 */
		public NULL|string $summary = "";
		
		/**
		 * Can be null.
		 * @var string
		 */
		public NULL|string $thanks_embed = "";
		
		/**
		 * Thank you message shown to patrons after they pledge to this campaign. Can be null.
		 * @var string
		 */
		public NULL|string $thanks_msg = "";
		
		/**
		 * URL for the video shown to patrons after they pledge to this campaign. Can be null.
		 * @var string
		 */
		public NULL|string $thanks_video_url = "";
		
		/**
		 * A URL to access this campaign on patreon.com
		 * @var string
		 */
		public NULL|string $url = "";
		
		/**
		 * The campaign's vanity. Can be null.
		 * @var string
		 */
		public NULL|string $vanity = "";

		/**
		 * The campaign's benefits.
		 * @var array
		 */
		public NULL|array $benefits = [];
		
		/**
		 * The campaign's installations.
		 * @var array
		 */
		public NULL|array $campaign_installations = [];
		
		/**
		 * The campaign's categories.
		 * @var array
		 */
		public NULL|array $categories = [];
		
		/**
		 * The campaign owner.
		 * @var \Patreonomy\Resource\User
		 */
		public NULL|\Patreonomy\Resource\User $creator = NULL;
		
		/**
		 * The campaign's goals.
		 * @var array
		 */
		public NULL|array $goals = [];
		
		/**
		 * The campaign's tiers.
		 * @var array
		 */
		public NULL|array $tiers = [];
		
		/**
		 * The campaign's members.
		 * @var array
		 */
		public NULL|array $members = [];
		
		/**
		 * The campaign's posts.
		 * @var array
		 */
		public NULL|array $posts = [];

		/**
		 * Get the data for this resource
		 * @return self
		 */
		public function get(...$arguments) : self {
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

				// TODO: Figure out what this is for (IT IS UNDOCUMENTED)
				//"campaign_installations",
			];

			if (\in_array("categories", $includes)) {
				$this->__parent->getCategories();
			}

			return parent::get(
				endpoint: \Patreonomy\Patreonomy::ENDPOINT_API . "/campaigns/" . $this->getId(),
				fields:   $fields,
				includes: $includes,
			);
		}

		/**
		 * Gets the Members for a given Campaign. Requires the campaigns.members scope
		 * @param  array  $fields   Array of field flags
		 * @param  array  $includes Array of include flags
		 * @return array            Array of Member objects
		 */
		public function getMembers(...$arguments) : array {
			if (empty($this->members)) {
				\extract($arguments);

				$fields ??= [
					"member" => \Patreonomy\Resource\Member::ALL_FIELD_FLAGS,
				];

				$includes ??= [
					"address",
					"currently_entitled_tiers",
					"user",
				];

				$this->members = $this->__parent->getResources(
					resource: "Member",
					endpoint: \Patreonomy\Patreonomy::ENDPOINT_API . "/campaigns/" . $this->getId() . "/members",
					fields:   $fields,
					includes: $includes,
				);
			}

			return $this->members;
		}

		/**
		 * Get a list of all the Posts on a given Campaign by campaign ID. Requires the campaigns.posts scope
		 * @param  array  $fields   Array of field flags
		 * @param  array  $includes Array of include flags
		 * @return array            Array of Post objects
		 */
		public function getPosts(...$arguments) : array {
			if (empty($this->posts)) {
				\extract($arguments);

				$fields ??= [
					"user" => \Patreonomy\Resource\User::ALL_FIELD_FLAGS,
					"post" => \Patreonomy\Resource\Post::ALL_FIELD_FLAGS,
				];

				$includes ??= [
					"campaign",
					"user",
				];

				$this->posts = $this->__parent->getResources(
					resource: "Post",
					endpoint: \Patreonomy\Patreonomy::ENDPOINT_API . "/campaigns/" . $this->getId() . "/posts",
					fields:   $fields,
					includes: $includes,
				);
			}

			return $this->posts;
		}
	}

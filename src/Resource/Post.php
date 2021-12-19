<?php

	namespace Patreonomy\Resource;

	final class Post extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "app_id", "app_status", "content", "embed_data", "embed_url", "is_paid", "is_public", "published_at", "title", "url" ];

		/**
		 * Platform app id. Can be null.
		 * @var int
		 */
		public NULL|int $app_id = 0;
		
		/**
		 * Processing status of the post. Can be null.
		 * @var string
		 */
		public NULL|string $app_status = "";
		
		/**
		 * Can be null.
		 * @var string
		 */
		public NULL|string $content = "";
		
		/**
		 * An object containing embed data if media is embedded in the post,None if there is no embed
		 * @var array
		 */
		public NULL|array $embed_data = [];
		
		/**
		 * Embed media url Can be null.
		 * @var string
		 */
		public NULL|string $embed_url = "";
		
		/**
		 * True if the post incurs a bill as part of a pay-per-post campaign Can be null.
		 * @var bool
		 */
		public NULL|bool $is_paid = false;
		
		/**
		 * True if the post is viewable by anyone,False if only patrons (or a subset of patrons) can view Can be null.
		 * @var bool
		 */
		public NULL|bool $is_public = false;
		
		/**
		 * (UTC ISO format)	Datetime that the creator most recently published (made publicly visible) the post. Can be null.
		 * @var string
		 */
		public NULL|string $published_at = "";
		
		/**
		 * Can be null.
		 * @var string
		 */
		public NULL|string $title = "";
		
		/**
		 * A URL to access this post on patreon.com
		 * @var string
		 */
		public NULL|string $url = "";
		
		/**
		 * The author of the post.
		 * @var \Patreonomy\Resource\User
		 */
		public NULL|\Patreonomy\Resource\User $user = NULL;
		
		/**
		 * The campaign that the membership is for.
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;

		/**
		 * The post's attachments
		 * TODO: Poke around and find the correct endpoint/fields/includes combination (if any)
		 * @var array
		 */
		public NULL|array $attachments = [];

		/**
		 * The post's comments
		 * @var array
		 */
		public NULL|array $comments = [];

		/**
		 * The post's tags
		 * TODO: Figure out what the heck is going on with post tags
		 * @var array
		 */
		public NULL|array $tags = [];

		/**
		 * Get the data for this resource
		 * @return self
		 */
		public function get(...$arguments) : self {
			\extract($arguments);

			$fields ??= [
				"campaign" => \Patreonomy\Resource\Campaign::ALL_FIELD_FLAGS,
				"user"     => \Patreonomy\Resource\User::ALL_FIELD_FLAGS,
				"post"     => \Patreonomy\Resource\Post::ALL_FIELD_FLAGS,
			];

			$includes ??= [
				"campaign",
				"user",
			];

			return parent::get(
				endpoint: \Patreonomy\Patreonomy::ENDPOINT_API . "/posts/" . $this->getId(),
				fields:   $fields,
				includes: $includes,
			);
		}

		/**
		 * Get this post's comments
		 * @param  array  $fields   Array of field flags
		 * @param  array  $includes Array of include flags
		 * @return array            Array of Comment objects
		 */
		public function getComments(...$arguments) : array {
			if (empty($this->comments)) {
				\extract($arguments);

				$fields   ??= [];
				$includes ??= [];

				$this->comments = $this->__parent->getResources(
					resource: "Comment",
					endpoint: \Patreonomy\Patreonomy::ENDPOINT_LEGACY . "/posts/" . $this->getId() . "/comments",
					fields:   $fields,
					includes: $includes,
				);
			}

			return $this->comments;
		}
	}

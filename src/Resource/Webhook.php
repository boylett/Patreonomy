<?php

	namespace Patreonomy\Resource;

	final class Webhook extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "last_attempted_at", "num_consecutive_times_failed", "paused", "secret", "triggers", "uri" ];

		/**
		 * Triggered when a new member is created. Note that you may get more than one of these per patron if they delete and renew their membership. Member creation only occurs if there was no prior payment between patron and creator.
		 * @var string
		 */
		const TRIGGER_MEMBERS_CREATE = "members:create";
		
		/**
		 * Triggered when the membership information is changed. Includes updates on payment charging events
		 * @var string
		 */
		const TRIGGER_MEMBERS_UPDATE = "members:update";
		
		/**
		 * Triggered when a membership is deleted. Note that you may get more than one of these per patron if they delete and renew their membership. Deletion only occurs if no prior payment happened, otherwise pledge deletion is an update to member status.
		 * @var string
		 */
		const TRIGGER_MEMBERS_DELETE = "members:delete";
		
		/**
		 * Triggered when a new pledge is created for a member. This includes when a member is created through pledging, and when a follower becomes a patron.
		 * @var string
		 */
		const TRIGGER_MEMBERS_PLEDGE_CREATE = "members:pledge:create";
		
		/**
		 * Triggered when a member updates their pledge.
		 * @var string
		 */
		const TRIGGER_MEMBERS_PLEDGE_UPDATE = "members:pledge:update";
		
		/**
		 * Triggered when a member deletes their pledge.
		 * @var string
		 */
		const TRIGGER_MEMBERS_PLEDGE_DELETE = "members:pledge:delete";
		
		/**
		 * Triggered when a post is published on a campaign.
		 * @var string
		 */
		const TRIGGER_POSTS_PUBLISH = "posts:publish";
		
		/**
		 * Triggered when a post is updated on a campaign.
		 * @var string
		 */
		const TRIGGER_POSTS_UPDATE = "posts:update";
		
		/**
		 * Triggered when a post is deleted on a campaign.
		 * @var string
		 */
		const TRIGGER_POSTS_DELETE = "posts:delete";

		/**
		 * (UTC ISO format)	Last date that the webhook was attempted or used.
		 * @var string
		 */
		public NULL|string $last_attempted_at = "";
		
		/**
		 * Number of times the webhook has failed consecutively, when in an error state.
		 * @var int
		 */
		public NULL|int $num_consecutive_times_failed = 0;
		
		/**
		 * true if the webhook is paused as a result of repeated failed attempts to post to uri. Set to false to attempt to re-enable a previously failing webhook.
		 * @var bool
		 */
		public NULL|bool $paused = false;
		
		/**
		 * Secret used to sign your webhook message body, so you can validate authenticity upon receipt.
		 * @var string
		 */
		public NULL|string $secret = "";
		
		/**
		 * List of events that will trigger this webhook.
		 * @var array
		 */
		public NULL|array $triggers = [];
		
		/**
		 * Fully qualified uri where webhook will be sent (e.g. https://www.example.com/webhooks/incoming).
		 * @var string
		 */
		public NULL|string $uri = "";
		
		/**
		 * The campaign whose events trigger the webhook.
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
		
		/**
		 * The client which created the webhook
		 * @var \Patreonomy\Resource\OAuthClient
		 */
		public NULL|\Patreonomy\Resource\OAuthClient $client = NULL;

		/**
		 * Modify this webhook (UNTESTED AND UNDOCUMENTED)
		 * @param  string $uri      The webhook's destination URI
		 * @param  array  $triggers Array of webhook triggers (see constants in \Patreonomy\Resource\Webhook)
		 * @param  bool   $paused   Set to 'false' to unpause the webhook and perform all queued actions at once
		 * @return self
		 */
		public function modify(...$arguments) : self {
			$uri      ??= "";
			$paused   ??= false;
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

			$response = $this->request(
				endpoint: static::ENDPOINT_API . "/webhooks/" . $this->getId(),
				type:     "PATCH",
				data:     [
					"data" => [
						"id"            => $this->getId(),
						"type"          => "webhook",
						"attributes"    => [
							"triggers" => $triggers,
							"uri"      => $uri,
							"paused"   => $paused,
						],
					],
				],
			);

			// TODO: Do something with the webhook response

			return $this;
		}
	}

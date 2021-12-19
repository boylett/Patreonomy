<?php

	namespace Patreonomy\Resource;

	final class Goal extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "amount_cents", "completed_percentage", "created_at", "description", "reached_at", "title" ];

		/**
		 * Goal amount in USD cents.
		 * @var int
		 */
		public NULL|int $amount_cents = 0;
		
		/**
		 * Equal to (pledge_sum/goal amount)*100, helpful when a creator
		 * @var int
		 */
		public NULL|int $completed_percentage = 0;
		
		/**
		 * (UTC ISO format)	When the goal was created for the campaign.
		 * @var string
		 */
		public NULL|string $created_at = "";
		
		/**
		 * Goal description. Can be null.
		 * @var string
		 */
		public NULL|string $description = "";
		
		/**
		 * (UTC ISO format)	When the campaign reached the goal. Can be null.
		 * @var string
		 */
		public NULL|string $reached_at = "";
		
		/**
		 * Goal title.
		 * @var string
		 */
		public NULL|string $title = "";
		
		/**
		 * The campaign trying to reach the goal
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
	}

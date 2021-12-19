<?php

	namespace Patreonomy\Resource;

	final class PledgeEvent extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "amount_cents", "currency_code", "date", "payment_status", "tier_id", "tier_title", "type" ];

		/**
		 * Amount (in the currency in which the patron paid) of the underlying event.
		 * @var int
		 */
		public NULL|int $amount_cents = 0;
		
		/**
		 * ISO code of the currency of the event.
		 * @var string
		 */
		public NULL|string $currency_code = "";
		
		/**
		 * (UTC ISO format)	The date which this event occurred.
		 * @var string
		 */
		public NULL|string $date = "";
		
		/**
		 * Status of underlying payment. One of Paid, Declined, Deleted, Pending, Refunded, Fraud, Other
		 * @var string
		 */
		public NULL|string $payment_status = "";
		
		/**
		 * Id of the tier associated with the pledge.
		 * @var string
		 */
		public NULL|string $tier_id = "";
		
		/**
		 * Title of the reward tier associated with the pledge.
		 * @var string
		 */
		public NULL|string $tier_title = "";
		
		/**
		 * Event type. One of pledge_start, pledge_upgrade, pledge_downgrade, pledge_delete, subscription
		 * @var string
		 */
		public NULL|string $type = "";
		
		/**
		 * The campaign being pledged to.
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
		
		/**
		 * The pledging user
		 * @var \Patreonomy\Resource\User
		 */
		public NULL|\Patreonomy\Resource\User $patron = NULL;
		
		/**
		 * The tier associated with this pledge event.
		 * @var \Patreonomy\Resource\Tier
		 */
		public NULL|\Patreonomy\Resource\Tier $tier = NULL;
	}

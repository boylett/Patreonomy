<?php

	namespace Patreonomy\Resource;

	final class Member extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "campaign_lifetime_support_cents", "currently_entitled_amount_cents", "email", "full_name", "is_follower", "last_charge_date", "last_charge_status", "lifetime_support_cents", "next_charge_date", "note", "patron_status", "pledge_cadence", "pledge_relationship_start", "will_pay_amount_cents" ];

		/**
		 * The total amount that the member has ever paid to the campaign in campaign's currency. 0 if never paid.
		 * @var int
		 */
		public NULL|int $campaign_lifetime_support_cents = 0;
		
		/**
		 * The amount in cents that the member is entitled to.This includes a current pledge, or payment that covers the current payment period.
		 * @var int
		 */
		public NULL|int $currently_entitled_amount_cents = 0;
		
		/**
		 * The member's email address. Requires the campaigns.members[email] scope.
		 * @var string
		 */
		public NULL|string $email = "";
		
		/**
		 * Full name of the member user.
		 * @var string
		 */
		public NULL|string $full_name = "";
		
		/**
		 * The user is not a pledging patron but has subscribed to updates about public posts.
		 * @var bool
		 */
		public NULL|bool $is_follower = false;
		
		/**
		 * (UTC ISO format)	Datetime of last attempted charge. null if never charged. Can be null.
		 * @var string
		 */
		public NULL|string $last_charge_date = "";
		
		/**
		 * The result of the last attempted charge.The only successful status is Paid.null if never charged. One of Paid, Declined, Deleted, Pending, Refunded, Fraud, Other. Can be null.
		 * @var string
		 */
		public NULL|string $last_charge_status = "";
		
		/**
		 * The total amount that the member has ever paid to the campaign. 0 if never paid.
		 * @var int
		 */
		public NULL|int $lifetime_support_cents = 0;
		
		/**
		 * (UTC ISO format)	Datetime of next charge. null if annual pledge downgrade. Can be null
		 * @var string
		 */
		public NULL|string $next_charge_date = "";
		
		/**
		 * The creator's notes on the member.
		 * @var string
		 */
		public NULL|string $note = "";
		
		/**
		 * One of active_patron, declined_patron, former_patron. A null value indicates the member has never pledged. Can be null.
		 * @var string
		 */
		public NULL|string $patron_status = "";
		
		/**
		 * Number of months between charges.
		 * @var int
		 */
		public NULL|int $pledge_cadence = 0;
		
		/**
		 * (UTC ISO format)	Datetime of beginning of most recent pledge chainfrom this member to the campaign. Pledge updates do not change this value. Can be null.
		 * @var string
		 */
		public NULL|string $pledge_relationship_start = "";
		
		/**
		 * The amount in cents the user will pay at the next pay cycle.
		 * @var int
		 */
		public NULL|int $will_pay_amount_cents = 0;
		
		/**
		 * The member's shipping address that they entered for the campaign.Requires the campaign.members.address scope.
		 * @var \Patreonomy\Resource\Address
		 */
		public NULL|\Patreonomy\Resource\Address $address = NULL;
		
		/**
		 * The campaign that the membership is for.
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
		
		/**
		 * The tiers that the member is entitled to. This includes a current pledge, or payment that covers the current payment period.
		 * @var array
		 */
		public NULL|array $currently_entitled_tiers = [];
		
		/**
		 * The pledge history of the member
		 * @var array
		 */
		public NULL|array $pledge_history = [];
		
		/**
		 * The user who is pledging to the campaign.
		 * @var \Patreonomy\Resource\User
		 */
		public NULL|\Patreonomy\Resource\User $user = NULL;

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
				endpoint: \Patreonomy\Patreonomy::ENDPOINT_API . "/members/" . $this->getId(),
				fields:   $fields ?: [
					"address" => \Patreonomy\Resource\Address::ALL_FIELD_FLAGS,
					"benefit" => \Patreonomy\Resource\Benefit::ALL_FIELD_FLAGS,
					"member"  => \Patreonomy\Resource\Member::ALL_FIELD_FLAGS,
					"tier"    => \Patreonomy\Resource\Tier::ALL_FIELD_FLAGS,
					"user"    => \Patreonomy\Resource\User::ALL_FIELD_FLAGS,
				],
				includes: $includes ?: [
					"address",
					"campaign",
					"currently_entitled_tiers",
					"currently_entitled_tiers.benefits",
					"currently_entitled_tiers.campaign",
					"user",
				],
			);
		}

		/**
		 * Search the member's tiers list
		 * @param  array ...$filters Filters
		 * @return array             Array of matching Tier objects
		 */
		public function searchTiers(...$filters) : array {
			return \Patreonomy\Patreonomy::searchArray($this->get()->getCurrentlyEntitledTiers(), $filters);
		}
	}

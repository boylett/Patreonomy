<?php

	namespace Patreonomy\Resource;

	final class Deliverable extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "completed_at", "delivery_status", "due_at" ];
		
		/**
		 * When the creator marked the deliverable as completed or fulfilled to the patron. Can be null. (UTC ISO format)
		 * @var string
		 */
		public NULL|string $completed_at = "";
		
		/**
		 * One of delivered, not_delivered, wont_deliver.
		 * @var string
		 */
		public NULL|string $delivery_status = "";
		
		/**
		 * When the deliverable is due to the patron. (UTC ISO format)
		 * @var string
		 */
		public NULL|string $due_at = "";
		
		/**
		 * The Benefit the Deliverables were generated for.
		 * @var \Patreonomy\Resource\Benefit
		 */
		public NULL|\Patreonomy\Resource\Benefit $benefit = NULL;
		
		/**
		 * The Campaign the Deliverables were generated for.
		 * @var \Patreonomy\Resource\Campaign
		 */
		public NULL|\Patreonomy\Resource\Campaign $campaign = NULL;
		
		/**
		 * The member who has been granted the deliverable.
		 * @var \Patreonomy\Resource\Member
		 */
		public NULL|\Patreonomy\Resource\Member $member = NULL;
		
		/**
		 * The user who has been granted the deliverable. This user is the same as the member user.
		 * @var \Patreonomy\Resource\User
		 */
		public NULL|\Patreonomy\Resource\User $user = NULL;
	}

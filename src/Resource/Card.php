<?php

	namespace Patreonomy\Resource;

	final class Card extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "card_type", "expiration_date", "links", "number", "type" ];

		/**
		 * Name of payment method. One of "PayPal" or "Visa"
		 * @var string
		 */
		public NULL|string $card_type = "";
		
		/**
		 * Eexpiration date of card. Can be null.
		 * @var string
		 */
		public NULL|string $expiration_date = "";
		
		/**
		 * Linked data
		 * @var array
		 */
		public NULL|array $links = [];
		
		/**
		 * Last four digits of credit card. Will be 0 for PayPal.
		 * @var string
		 */
		public NULL|string $number = "";
		
		/**
		 * Card type
		 * @var string
		 */
		public NULL|string $type = "card";
	}

<?php

	namespace Patreonomy\Resource;

	final class Address extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "addressee", "city", "country", "created_at", "line_1", "line_2", "phone_number", "postal_code", "state" ];

		/**
		 * Full recipient name. Can be null.
		 * @var string
		 */
		public NULL|string $addressee = "";
		
		/**
		 * City.
		 * @var string
		 */
		public NULL|string $city = "";
		
		/**
		 * Country.
		 * @var string
		 */
		public NULL|string $country = "";
		
		/**
		 * (UTC ISO format)	Datetime address was first created.
		 * @var string
		 */
		public NULL|string $created_at = "";
		
		/**
		 * First line of street address. Can be null.
		 * @var string
		 */
		public NULL|string $line_1 = "";
		
		/**
		 * Second line of street address. Can be null.
		 * @var string
		 */
		public NULL|string $line_2 = "";
		
		/**
		 * Telephone number. Specified for non-US addresses. Can be null.
		 * @var string
		 */
		public NULL|string $phone_number = "";
		
		/**
		 * Postal or zip code. Can be null.
		 * @var string
		 */
		public NULL|string $postal_code = "";
		
		/**
		 * State or province name. Can be null.
		 * @var string
		 */
		public NULL|string $state = "";
		
		/**
		 * The campaigns that have access to the address.
		 * @var array
		 */
		public NULL|array $campaigns = [];
		
		/**
		 * The user this address belongs to.
		 * @var \Patreonomy\Resource\User
		 */
		public NULL|\Patreonomy\Resource\User $user = NULL;

		/**
		 * When the instance is casted to a string
		 * @return string
		 */
		public function __toString() : string {
			return \implode("\n", \array_filter([
				$this->getAddressee() ?: NULL,
				$this->getLine1() ?: NULL,
				$this->getLine2() ?: NULL,
				$this->getCity() ?: NULL,
				$this->getState() ?: NULL,
				$this->getCountry() ?: NULL,
				$this->getPostalCode() ?: NULL,
			]));
		}
	}

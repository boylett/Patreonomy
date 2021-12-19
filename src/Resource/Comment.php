<?php

	namespace Patreonomy\Resource;

	final class Comment extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "body", "created", "deleted_at", "is_by_creator", "is_by_patron", "vote_sum" ];

        /**
         * Undocumented
         * @var string
         */
        public NULL|string $body = "";

        /**
         * Undocumented
         * @var string
         */
        public NULL|string $created = "";

        /**
         * Undocumented
         * @var string
         */
        public NULL|string $deleted_at = "";

        /**
         * Undocumented
         * @var bool
         */
        public NULL|bool $is_by_creator = false;

        /**
         * Undocumented
         * @var bool
         */
        public NULL|bool $is_by_patron = false;

        /**
         * Undocumented
         * @var int
         */
        public NULL|int $vote_sum = 0;

        /**
         * Undocumented
         * @var \Patreonomy\Resource\User
         */
        public NULL|\Patreonomy\Resource\User $commenter = NULL;

        /**
         * Undocumented
         * @var mixed
         */
        public $parent = NULL;

        /**
         * Undocumented
         * @var \Patreonomy\Resource\Post
         */
        public NULL|\Patreonomy\Resource\Post $post = NULL;

        /**
         * Undocumented
         * @var array
         */
        public NULL|array $replies = [];

		/**
		 * Get the data for this resource
         * @legacy This method uses a v1 API endpoint
         * @param  array $fields   Array of field flags
         * @param  array $includes Array of include flags
		 * @return self
		 */
		public function get(
            array $fields   = [],
            array $includes = [],
        ) : self {
			return parent::__getData(
				endpoint: \Patreonomy\Patreonomy::ENDPOINT_LEGACY . "/comments/" . $this->getId(),
				fields:   $fields,
				includes: $includes,
			);
		}
	}

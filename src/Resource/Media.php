<?php

	namespace Patreonomy\Resource;

	final class Media extends \Patreonomy\Resource\AbstractResource {
		/**
		 * Default field flags set
		 * @var array
		 */
		const ALL_FIELD_FLAGS = [ "created_at", "download_url", "file_name", "image_urls", "metadata", "mimetype", "owner_id", "owner_relationship", "owner_type", "size_bytes", "state", "upload_expires_at", "upload_parameters", "upload_url" ];
		
		/**
		 * When the file was created. (UTC ISO format)
		 * @var string
		 */
		public NULL|string $created_at = "";
		
		/**
		 * The URL to download this media. Valid for 24 hours.
		 * @var string
		 */
		public NULL|string $download_url = "";
		
		/**
		 * File name.
		 * @var string
		 */
		public NULL|string $file_name = "";
		
		/**
		 * The resized image URLs for this media. Valid for 2 weeks.
		 * @var array
		 */
		public NULL|array $image_urls = [];
		
		/**
		 * Metadata related to the file. Can be null.
		 * @var array
		 */
		public NULL|array $metadata = [];
		
		/**
		 * Mimetype of uploaded file, eg: "application/jpeg".
		 * @var string
		 */
		public NULL|string $mimetype = "";
		
		/**
		 * Ownership id (See also owner_type).
		 * @var string
		 */
		public NULL|string $owner_id = "";
		
		/**
		 * Ownership relationship type for multi-relationship medias.
		 * @var string
		 */
		public NULL|string $owner_relationship = "";
		
		/**
		 * Type of the resource that owns the file.
		 * @var string
		 */
		public NULL|string $owner_type = "";
		
		/**
		 * Size of file in bytes.
		 * @var int
		 */
		public NULL|int $size_bytes = 0;
		
		/**
		 * The state of the file.
		 * @var string
		 */
		public NULL|string $state = "";
		
		/**
		 * When the upload URL expires. (UTC ISO format)
		 * @var string
		 */
		public NULL|string $upload_expires_at = "";
		
		/**
		 * All the parameters that have to be added to the upload form request.
		 * @var array
		 */
		public NULL|array $upload_parameters = [];
		
		/**
		 * The URL to perform a POST request to in order to upload the media file.
		 * @var string
		 */
		public NULL|string $upload_url = "";
	}

<?php

	namespace Patreonomy\Response;

	class Exception extends \Exception {
		/**
		 * 400 error message
		 * @var string
		 */
		const ERROR_400 = "Bad Request – Something was wrong with your request (syntax, size too large, etc.)";
		
		/**
		 * 401 error message
		 * @var string
		 */
		const ERROR_401 = "Unauthorized – Authentication failed (bad API key, invalid OAuth token, incorrect scopes, etc.)";
		
		/**
		 * 403 error message
		 * @var string
		 */
		const ERROR_403 = "Forbidden – The requested is hidden for administrators only.";
		
		/**
		 * 404 error message
		 * @var string
		 */
		const ERROR_404 = "Not Found – The specified resource could not be found.";
		
		/**
		 * 405 error message
		 * @var string
		 */
		const ERROR_405 = "Method Not Allowed – You tried to access a resource with an invalid method.";
		
		/**
		 * 406 error message
		 * @var string
		 */
		const ERROR_406 = "Not Acceptable – You requested a format that isn't json.";
		
		/**
		 * 410 error message
		 * @var string
		 */
		const ERROR_410 = "Gone – The resource requested has been removed from our servers.";
		
		/**
		 * 429 error message
		 * @var string
		 */
		const ERROR_429 = "Too Many Requests – Slow down!";
		
		/**
		 * 500 error message
		 * @var string
		 */
		const ERROR_500 = "Internal Server Error – Our server ran into a problem while processing this request. Please try again later.";
		
		/**
		 * 503 error message
		 * @var string
		 */
		const ERROR_503 = "Service Unavailable – We're temporarily offline for maintenance. Please try again later.";

		/**
		 * Error message
		 * @var string
		 */
		protected $message = "Patreonomy exception";
	}

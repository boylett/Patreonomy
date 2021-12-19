<?php

	namespace Patreonomy\Resource;

	abstract class AbstractResource {
		/**
		 * Parent controller instance
		 * @var \Patreonomy\Patreonomy
		 */
		public \Patreonomy\Patreonomy $__parent;

		/**
		 * Resource cache
		 * @var array
		 */
		protected static array $cache = [];

		/**
		 * Resource ID
		 * @var string
		 */
		public string $id;

		/**
		 * Whether this resource's attributes have been fully populated
		 * @var bool
		 */
		public bool $populated = false;

		/**
		 * Build an API-compatible set of fields & includes
         * @param  array $fields   Array of field flags
         * @param  array $includes Array of include flags
		 * @return array           API-compatible array
		 */
		public static function buildFields(
			array $fields   = [],
			array $includes = [],
		) : array {
			$query = [];

			foreach ($fields as $resource => $properties) {
				\sort($properties);

				$query["fields[" . $resource . "]"] = \implode(",", \array_filter(\array_unique($properties)));
			}

			if (!empty($includes)) {
				\sort($includes);
				
				$query["include"] = \implode(",", \array_filter(\array_unique($includes)));
			}

			\ksort($query);

			return $query;
		}

		/**
		 * Property getter
		 * @param  string $method    Method name
		 * @param  array  $arguments Method arguments
		 * @return mixed             Property value
		 */
		public function __call(
			string $method,
			array  $arguments = [],
		) : mixed {
			if (\preg_match("/^get([a-zA-Z0-9]+)$/i", $method, $property)) {
				$property = \strtolower(\preg_replace(["/([a-z0-9\d])([A-Z0-9])/", "/([^_])([A-Z0-9][a-z0-9])/"], "$1_$2", $property[1]));

				if (\property_exists($this, $property)) {
					return $this->{$property} ?? NULL;
				}

				else {
					throw new \Patreonomy\Response\Exception("Undefined property: \\" . \get_called_class() . "::" . $property);
				}

				return NULL;
			}

			throw new \Patreonomy\Response\Exception("Call to undefined method \\" . \get_called_class() . "::" . $method . "()");
		}

		/**
		 * Prune output when debugging
		 * @return array
		 */
		public function __debugInfo() : array {
			return $this->__toArray(true);
		}

		/**
		 * Get the data for this resource
         * @param  string $endpoint Full resource endpoint
         * @param  array  $fields   Array of field flags
         * @param  array  $includes Array of include flags
		 * @return self
		 */
		public function __getData(
            string $endpoint,
            array  $fields   = [],
            array  $includes = [],
		) : self {
			if (!$this->populated) {
				$response = $this->__parent->request(
					endpoint: $endpoint,
					type:     "GET",
					data:     static::buildFields($fields, $includes),
				);

				$this->__populate($response);
			}

			return $this;
		}

		/**
		 * Retrieve a cached instance or create a new one
		 * @param  string                                $id Resource ID
		 * @return \Patreonomy\Resource\AbstractResource     Resource object
		 */
		public static function __instance(string $id) : static {
			$class = \get_called_class();

			if (!isset(static::$cache[$class][$id])) {
				static::$cache[$class][$id] = new static();

				static::$cache[$class][$id]->id = $id;
			}

			return static::$cache[$class][$id];
		}

		/**
		 * Populate the object with a set of data
		 * @param  array $data Data to populate
		 * @return self
		 */
		public function __populate(array $data) : self {
			if (empty($data["data"])) {
				return $this;
			}

			if (!empty(($data["data"]["attributes"] ?? []) ?: [])) {
				$this->populated = true;

				foreach ($data["data"]["attributes"] as $key => $value) {
					if (isset($this->{$key})) {
						$this->{$key} = $value;
					}
				}
			}

			$type_types = [];

			foreach (($data["data"]["relationships"] ?? []) ?: [] as $type => $relationships) {
				if (\property_exists($this, $type)) {
					if (!isset($type_types[$type])) {
						$reflection        = new \ReflectionProperty($this, $type);
						$reflection_type   = $reflection->getType();
						$type_types[$type] = $reflection_type?->getName() ?: "mixed";
					}

					if (isset($relationships["data"]["id"])) {
						$relationships = [
							"data" => [
								$relationships["data"],
							],
						];
					}

					foreach ($relationships["data"] ?: [] as $relationship) {
						$instance = NULL;

						switch ($relationship["type"]) {
							case "address": {
								$instance = $this->__parent->Address($relationship["id"]);
							}

							break;

							case "attachment": {
								$instance = $this->__parent->Attachment($relationship["id"]);
							}

							break;

							case "benefit": {
								$instance = $this->__parent->Benefit($relationship["id"]);
							}

							break;

							case "campaign": {
								$instance = $this->__parent->Campaign($relationship["id"]);
							}

							break;

							case "campaign_installation": {
								$instance = $this->__parent->CampaignInstallation($relationship["id"]);
							}

							break;

							case "card": {
								$instance = $this->__parent->Card($relationship["id"]);
							}

							break;

							case "category": {
								$instance = $this->__parent->Category($relationship["id"]);
							}

							break;

							case "comment": {
								$instance = $this->__parent->Comment($relationship["id"]);
							}

							break;

							case "deliverable": {
								$instance = $this->__parent->Deliverable($relationship["id"]);
							}

							break;

							case "goal": {
								$instance = $this->__parent->Goal($relationship["id"]);
							}

							break;

							case "media": {
								$instance = $this->__parent->Media($relationship["id"]);
							}

							break;

							case "member": {
								$instance = $this->__parent->Member($relationship["id"]);
							}

							break;

							case "pledge_event": {
								$instance = $this->__parent->PledgeEvent($relationship["id"]);
							}

							break;

							case "post": {
								$instance = $this->__parent->Post($relationship["id"]);
							}

							break;

							case "post_tag":
							case "user_defined_tags": {
								$instance = $this->__parent->PostTag($relationship["id"]);
							}

							break;

							case "currently_entitled_tiers":
							case "reward":
							case "tier": {
								$instance = $this->__parent->Tier($relationship["id"]);
							}

							break;

							case "user": {
								$instance = $this->__parent->User($relationship["id"]);
							}

							break;

							case "webhook": {
								$instance = $this->__parent->Webhook($relationship["id"]);
							}

							break;
						}

						if ($instance) {
							if ($type_types[$type] === "array" and \in_array($instance, $this->{$type})) {
								continue;
							}

							else if ($this->{$type} === $instance) {
								continue;
							}

							foreach ($data["included"] ?? [] as $include) {
								if ((($include["id"] ?? "") === $relationship["id"]) and (($include["type"] ?? "") === $relationship["type"])) {
									$instance->__populate([ "data" => $include ]);

									break;
								}
							}

							if ($type_types[$type] === "array") {
								$this->{$type}[] = $instance;
							}

							else {
								$this->{$type} = $instance;
							}
						}

						else {
							throw new \Patreonomy\Response\Exception("Unexpected include type '" . $type . "'");
						}
					}
				}
			}

			return $this;
		}

		/**
		 * Do not include the parent if this instance is serialized
		 * @return array
		 */
		public function __serialize() : array {
			return $this->__toArray(true);
		}

		/**
		 * Recursively convert this instance to an array
		 * @param  bool  $is_serializing Whether we are normalizing this array for serialization
		 * @return array
		 */
		public function __toArray(bool $is_serializing = false) : array {
			$array = [];

			foreach (\get_object_vars($this) as $property => $value) {
				if (!\in_array($property, [
					"__parent",
				])) {
					if (!$is_serializing) {
						if ($value instanceof static) {
							$value = $value->__toArray();
						}

						if ($value instanceof \DateTime) {
							$value = $value->format("Y-m-d\TH:i:s\.uP");
						}

						else if (\is_object($value)) {
							$value = \json_decode(\json_encode($value), true);
						}
					}

					$array[$property] = $value;
				}
			}

			return $array;
		}

		/**
		 * Export this object as an array
		 * @param  array $keys Which properties to export
		 * @return array
		 */
		public function export(array $keys = []) : array {
			$array = $this->__toArray();

			unset($array["populated"]);

			if (!empty($keys)) {
				$export = [];

				foreach ($keys as $property) {
					$export[$property] = $array[$property];
				}

				return $export;
			}

			return $array;
		}
	}
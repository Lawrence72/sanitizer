<?php

namespace Lawrence72\Sanitizer;

use flight\util\Collection;

class Sanitizer {

	/**
	 * 
	 * @param mixed $data 
	 * @param array $allowed_tags 
	 * @return mixed 
	 * allowed tags should be in the format of ['b', 'i', 'u', 'a']
	 */
	public function clean($data, array $allowed_tags = []) {

		if (is_string($data)) {
			return $this->cleanStrings($data, $allowed_tags);
		} elseif ($data instanceof Collection) {
			return $this->cleanCollection($data, $allowed_tags);
		} elseif (is_array($data)) {
			return $this->cleanArray($data, $allowed_tags);
		} elseif (is_object($data)) {
			return $this->cleanObject($data, $allowed_tags);
		} else {
			return $data;
		}
	}

	protected function cleanStrings($data, array $allowed_tags = []) {
		$data = trim($data);

		$cleaned_data = empty($allowed_tags) ?
			htmlspecialchars(strip_tags($data)) :
			$this->cleanStringWithTags($data, $allowed_tags);

		return trim(preg_replace(
			'/[\x00-\x08\x0B\x0C\x0E-\x1F]/',
			'',
			$cleaned_data
		));
	}

	protected function cleanStringWithTags($data, array $allowed_tags = []) {
		$strip_tags = strip_tags($data, '<' . implode('><', $allowed_tags) . '>');

		$cleaned_data = preg_replace_callback(
			'/(<(?:'
				. implode('|', $allowed_tags)
				. ')[^>]*>)(.*?)(<\/(?:'
				. implode('|', $allowed_tags)
				. ')>)/is',
			function ($matches) {
				return  $matches[1] . htmlspecialchars($matches[2]) . $matches[3];
			},
			$strip_tags
		);

		return $cleaned_data;
	}

	protected function cleanCollection(Collection $data, array $allowed_tags = []) {
		$cleaned_data = [];
		foreach ($data as $key => $value) {
			$cleaned_data[$key] = $this->clean($value, $allowed_tags);
		}
		return new Collection($cleaned_data);
	}

	protected function cleanArray(array $data, array $allowed_tags = []) {
		$cleaned_data = [];
		foreach ($data as $key => $value) {
			$cleaned_data[$key] = $this->clean($value, $allowed_tags);
		}
		return $cleaned_data;
	}

	protected function cleanObject($data, array $allowed_tags = []) {
		$cleaned_data = new \stdClass();
		foreach ($data as $key => $value) {
			$cleaned_data->$key = $this->clean($value, $allowed_tags);
		}
		return $cleaned_data;
	}
}

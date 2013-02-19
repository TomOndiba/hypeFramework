<?php

class hjFile extends ElggFile {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['guid'] = null;
		$this->attributes['owner_guid'] = elgg_get_logged_in_user_guid();
		$this->attributes['container_guid'] = elgg_get_logged_in_user_guid();
		$this->attributes['type'] = 'object';
		$this->attributes['subtype'] = "hjfile";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	/**
	 * Get Icon URL
	 * @param str $size
	 * @return str
	 */
	public function getIconURL($size = 'medium') {
		if ($this->icontime) {
			return elgg_get_config('url') . "framework/icon/$this->guid/$size/$this->icontime.jpg";
		}
		return parent::getIconURL($size);
	}

	public function delete() {

		$icon_sizes = hj_framework_get_thumb_sizes($this->getSubtype());

		$prefix_old = "hjfile/$this->container_guid/$this->guid";
		$prefix = "icons/$this->guid";

		foreach ($icon_sizes as $size => $values) {
			$thumb = new ElggFile();
			$thumb->owner_guid = elgg_get_logged_in_user_guid();
			$thumb->setFilename("$prefix$size.jpg");
			$thumb->delete();

			$thumb = new ElggFile();
			$thumb->owner_guid = elgg_get_logged_in_user_guid();
			$thumb->setFilename("$prefix_old$size.jpg");
			$thumb->delete();
		}

		return parent::delete();
	}

}
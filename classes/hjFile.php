<?php

class hjFile extends ElggFile {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = "hjfile";
	}

	/**
	 * Get Icon URL
	 * @param str $size
	 * @return str
	 */
	public function getIconURL($size = 'medium') {
		if (isset($this->icontime)) {
			$url = "framework/icon/$this->guid/$size/$this->icontime.jpg";
		} else {
			$type = (isset($this->simpletype)) ? $this->simpletype : 'general';
			$url = "mod/hypeFramework/graphics/mime/{$size}/{$type}.png";
		}
		return elgg_normalize_url($url);
	}

	public function delete() {

		$icon_sizes = hj_framework_get_thumb_sizes($this->getSubtype());

		$prefix_old = "hjfile/$this->container_guid/$this->guid";
		$prefix_old_alt = "hjfile/$this->guid";
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

			$thumb = new ElggFile();
			$thumb->owner_guid = elgg_get_logged_in_user_guid();
			$thumb->setFilename("$prefix_old_alt$size.jpg");
			$thumb->delete();
		}

		return parent::delete();
	}

}
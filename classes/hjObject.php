<?php

class hjObject extends ElggObject {

	/**
	 * Perform generic actions and add generic metadata to the entity
	 * @return mixed
	 */
	public function save($ancestor_subtypes = null) {
		$return = parent::save();

		if ($return) {
			$this->setAncestry();
		}

		return $return;
	}

	/**
	 * Update ancestry relationships
	 * @see hj_framework_set_ancestry()
	 * @param mixed $subtypes	Array of object subtypes to update or null for all
	 * @return array			Hierarchy of ancestors
	 */
	public function setAncestry($subtypes = null) {
		return hj_framework_set_ancestry($this->guid, $subtypes);
	}

	public function getIconURL($size = 'medium') {
		if ($this->icontime) {
			return elgg_get_config('url') . "framework/icon/$this->guid/$size/$this->icontime.jpg";
		}
		parent::getIconURL($size);
	}

	public function fileIn($parent_guid = null) {
		return add_entity_relationship($this->guid, 'filed_in', $parent_guid);
	}

	public function getEditURL() {

	}

	public function addBookmark() {

	}

	public function removeBookmark() {

	}

	public function isBookmarked() {

	}

	public function addSubscription() {

	}

	public function removeSubscription() {

	}

	public function isSubscribed() {

	}

	public function logSummaryView() {

	}

	public function logView() {

	}

	public function canCreate() {

	}

	public function canRetrieve() {

	}

	public function canEdit() {

	}

	public function canUpdate() {

	}

	public function canDelete() {

	}

	public function canUseAsContainer() {

	}

	public function canComment() {

	}

	public function canAnnotate() {
		
	}

}
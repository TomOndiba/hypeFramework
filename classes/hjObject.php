<?php

class hjObject extends ElggObject implements hjLocationInterface, hjSubscriptionInterface, hjPermissionsInterface, hjAnalyticsInterface, hjHierarchyInterface {

	public function save() {

		$return = parent::save();

		if ($return) {
			$this->createHierarchyBreadcrumbs();
		}

		return $return;
	}

	public function createHierarchyBreadcrumbs() {
		hj_framework_create_hierarchy_breadcrumbs($this->guid);
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
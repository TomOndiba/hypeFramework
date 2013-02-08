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
	 * Delete entity
	 * @return mixed
	 */
	public function delete($recursive = true) {
		return parent::delete($recursive);
	}

	/**
	 * Get full view URL
	 */
	public function getURL() {
		$friendly_title = elgg_get_friendly_title($this->getTitle());
		return elgg_get_site_url() . "framework/view/$this->guid/$friendy_title";
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

	/**
	 * Get entity edit page URL
	 * @return str
	 */
	public function getEditURL() {
		return elgg_get_site_url() . "framework/edit/$this->guid";
	}

	/**
	 * Get entity delete action URL
	 * @return str
	 */
	public function getDeleteURL() {

		return elgg_add_action_tokens_to_url(elgg_get_site_url() . "action/framework/delete/object?guid=$this->guid");
	}

	/**
	 * Add entity to a category
	 * @param int $category_guid
	 * @param bool $recursive	Traverse up the category tree?
	 */
	public function setCategory($category_guid, $recursive = true) {
		if ($recursive) {
			$category = get_entity($category_guid);
			while ($category instanceof hjCategory) {
				if (!check_entity_relationship($this->guid, 'filed_in', $category_guid)) {
					add_entity_relationship($this->guid, 'filed_in', $category->guid);
				}
				if ($recursive) {
					$category = $category->getContainerEntity();
				} else {
					$category = null;
				}
			}
		}
	}

	/**
	 * Remove entity from category
	 * @param int $category_guid
	 * @param bool $recursive
	 */
	public function unsetCategory($category_guid, $recursive = true) {
		if ($recursive) {
			$category = get_entity($category_guid);
			while ($category instanceof hjCategory) {
				if (!check_entity_relationship($this->guid, 'filed_in', $category_guid)) {
					remove_entity_relationship($this->guid, 'filed_in', $category->guid);
				}
				if ($recursive) {
					$category = $category->getContainerEntity();
				} else {
					$category = null;
				}
			}
		}
	}

	/**
	 * Get categories
	 * @param mixed $subtype
	 */
	public function getCategories($subtypes = array('hjcategory')) {

		return elgg_get_entities_from_relationship(array(
					'types' => 'object',
					'subtypes' => $subtypes,
					'limit' => 0,
					'relationship' => 'filed_in',
					'relationship_guid' => $this->guid
				));
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

	/**
	 * Get entity title
	 * @return type
	 */
	public function getTitle() {
		if (isset($this->title_key) && !empty($this->title_key)) {
			return elgg_echo($this->title_key);
		}
		return $this->title;
	}

	/**
	 * Can user edit this entity?
	 * @param int $user_guid
	 * @return bool
	 */
	public function canEdit($user_guid = 0) {
		return parent::canEdit($user_guid);
	}

	/**
	 * Can user preview this entity?
	 * @param int $user_guid
	 * @return bool
	 */
	public function canPreview($user_guid = 0) {
		$user = get_user($user_guid);
		if (!$user)
			$user = elgg_get_logged_in_user_entity();

		$params = array(
			'user' => $user,
			'entity' => $this
		);

		return elgg_trigger_plugin_hook('permissions_check:preview', 'framework', $params, true);
	}

	/**
	 * Can user view full entity?
	 * @param int $user_guid
	 * @return bool
	 */
	public function canView($user_guid = 0) {
		$user = get_user($user_guid);
		if (!$user)
			$user = elgg_get_logged_in_user_entity();

		$params = array(
			'user' => $user,
			'entity' => $this
		);

		return elgg_trigger_plugin_hook('permissions_check:view', 'framework', $params, true);
	}

	/**
	 * Can user delete this entity?
	 * @param int $user_guid
	 * @return bool
	 */
	public function canDelete($user_guid = 0) {
		$user = get_user($user_guid);
		if (!$user)
			$user = elgg_get_logged_in_user_entity();

		$return = false;

		if (elgg_is_admin_logged_in() || $this->canEdit($user_guid)) {
			$return = true;
		}

		$params = array(
			'user' => $user,
			'entity' => $this
		);

		return elgg_trigger_plugin_hook('permissions_check:delete', 'framework', $params, $return);
	}

	/**
	 * Can user administer this entity?
	 * @param int $user_guid
	 * @return bool
	 */
	public function canAdminister($user_guid = 0) {
		$user = get_user($user_guid);
		if (!$user)
			$user = elgg_get_logged_in_user_entity();

		$return = false;

		if (elgg_is_admin_logged_in() || $this->canEdit($user_guid)) {
			$return = true;
		}

		$params = array(
			'user' => $user,
			'entity' => $this
		);

		return elgg_trigger_plugin_hook('permissions_check:administer', 'framework', $params, $return);
	}

	/**
	 * Can user moderate this entity?
	 * @param int $user_guid
	 * @return bool
	 */
	public function canModerate($user_guid = 0) {
		$user = get_user($user_guid);
		if (!$user)
			$user = elgg_get_logged_in_user_entity();

		$return = false;

		if (elgg_is_admin_logged_in() || $this->canEdit($user_guid)) {
			$return = true;
		}

		$params = array(
			'user' => $user,
			'entity' => $this
		);

		return elgg_trigger_plugin_hook('permissions_check:moderate', 'framework', $params, $return);
	}

	/**
	 * Can user this entity as container?
	 * @param int $user_guid
	 * @return bool
	 */
	public function canWriteToContainer($user_guid = 0, $type = 'all', $subtype = 'all') {
		if ($this->canEdit($user_guid) || $this->canModerate($user_guid) || $this->canAdminister($user_guid)) {
			return true;
		}
		return parent::canWriteToContainer($user_guid, $type, $subtype);
	}

	/**
	 * Log entity view as annotation
	 * @param string $name
	 * @return type
	 */
	public function logView() {
		return $this->annotate('log:view', 1, ACCESS_PUBLIC);
	}

	/**
	 * Log entity preview as annotation
	 * @param string $name
	 * @return type
	 */
	public function logPreview() {
		return $this->annotate('log:preview', 1, ACCESS_PUBLIC);
	}

	/**
	 * Count logged views
	 * @return int
	 */
	public function countTotalViews() {
		return $this->getAnnotationsSum('log:view');
	}

	/**
	 * Count logged previews
	 * @return int
	 */
	public function countTotalPreviews() {
		return $this->getAnnotationsSum('log:preview');
	}

}
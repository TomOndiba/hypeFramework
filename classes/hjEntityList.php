<?php
/**
 * Class to manage lists of entities
 *
 */
class hjEntityList {

	protected $id;

	protected $getter;
	protected $viewer;

	protected $getter_options;
	protected $viewer_options;

	function __construct($id) {
		$this->setID($id);
	}

	private function setID($id) {
		$this->id = $id;
	}

	public function getID() {
		return $this->id;
	}

	public function toggleListType($list_type) {



	}

	
}

?>

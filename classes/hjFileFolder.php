<?php

class hjFileFolder extends ElggObject {

    protected function initializeAttributes() {
        parent::initializeAttributes();

        $this->attributes['subtype'] = "hjfilefolder";
    }

    public function getContainedFiles($subtype = 'hjfile', $count = false) {
        $files = hj_framework_get_entities_by_priority('object', $subtype, null, $this->guid);
		if ($count) {
			$files = sizeof($files);
		}
        return $files;
    }

    public function getDataTypes() {
        $types = hj_formbuilder_get_filefolder_types();
        return $types;
    }

}
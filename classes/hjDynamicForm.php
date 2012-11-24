<?php

/**
 * 
 * Dynamic forms for Elgg
 *
 * @package hypeJunction
 * @subpackage hypeFramework
 * @author Ismayil Khayredinov (ismayil.khayredinov@gmail.com)
 *
 */
class hjDynamicForm {

	protected $name;
	protected $id;
	protected $action;
	protected $method;
	protected $enctype;
	protected $view;
	protected $class;
	protected $fieldsets;
	protected $inputs;
	protected $overwrite_inputs;
	protected $subject_entity;
	protected $subject_entity_attributes;
	protected $validation;
	protected $data;
	protected $disable_security = false;

	/**
	 * Construct the form object
	 *
	 * @param str $name Unique form name
	 * @param ElggEntity $entity An entity this form is trying to edit
	 * @param array $params Additional options to pass to the init hook
	 */
	function __construct($name, $entity = null, $params = array()) {

		$this->setName($name);
		$this->setDefaults($entity);
		$this->init($params);
		$this->prepareForm();
	}

	/**
	 * Allow plugins to modify the form and add inputs
	 *
	 * @param array $params
	 */
	private function init($params = array()) {
		$params['form'] = $this;
		$params['entity'] = $this->getSubjectEntity();
		elgg_trigger_plugin_hook('init', "form:{$this->getName()}", $params, null);
	}

	/**
	 * Set Default values for the form object
	 * @param array $options Alternative options to set by default
	 */
	private function setDefaults($entity = null) {

		$this->setAction('action/framework/form/submit');
		$this->setMethod('POST');
		//$this->setEnctype('text/plain'); Leave value empty, so that isMultipart() check is performed

		$entity_handler = hj_framework_get_form_assoc_options($this->getName());
		if (elgg_instanceof($entity)) {
			$this->setSubjectEntity($entity);
			$this->setSubjectEntityAttr('type', $entity->getType());
			$this->setSubjectEntityAttr('subtype', $entity->getSubtype());
		} else if ($entity_handler) {
			$this->setSubjectEntityAttr('type', $entity_handler['type']);
			$this->setSubjectEntityAttr('subtype', $entity_handler['subtype']);
		}

		$this->setView('page/components/forms/layouts/default');

		$this->registerFieldset('default', array(
			'priority' => 500,
				// 'title' => elgg_echo('fildset:title'),
				// 'class' => 'fieldset-class',
				// 'legend' => elgg_echo('fieldset:legend'),
				// 'overwrite_view' => false,
				// 'data-tag-attribute' => 'something to add here'
		));

		// Let's hold default fieldset for all form buttons, e.g. submit, reset, cancel etc
		$this->registerFieldset('footer', array(
			'priority' => 999
		));
	}

	/**
	 * Set the form name
	 * @return void
	 */
	private function setName($name) {
		$this->name = $name;
		$this->registerInput('form_name', 'hidden', false, array(
			'value' => $name,
			'fieldset' => 'footer'
		));
	}

	/**
	 * Get the form name
	 * @return str
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set the form action
	 * @param str $action
	 */
	public function setAction($action = '') {
		if (empty($action)) {
			$action = elgg_get_site_url() . 'action/' . str_replace(':', '/', $this->getName());
		}
		$action_name = str_replace('action/', '', $action);
		if (elgg_action_exists($action_name)) {
			$this->action = $action;
		}
	}

	/**
	 * Get the form action
	 * @return str
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Set the form method
	 * @param str $method
	 */
	public function setMethod($method = 'GET') {
		$this->method = $method;
	}

	/**
	 * Get the form method
	 * @return str
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Set the form wrapper view
	 * @param str $view
	 */
	public function setView($view) {
		$this->view = $view;
	}

	/**
	 * Get form wrapper view
	 * @return mixed
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * Set form class
	 * @param str $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	 * Get form class
	 * @return mixed
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * Set form id
	 *
	 * @param str $id
	 */
	public function setID($id) {
		$this->id = $id;
	}

	/**
	 * Get form id
	 * @return mixed
	 */
	public function getID() {
		if (!$this->id) {
			$id = str_replace(':', '-', "elgg-form-edit-{$this->getName()}");
			$this->setID($id);
		}
		return $this->id;
	}

	/**
	 * Set a form enctype
	 * @param str $enctype
	 */
	public function setEnctype($enctype) {
		$this->enctype = $enctype;
	}

	/**
	 * Get form enctype
	 * @return mixed
	 */
	public function getEnctype() {
		return (!$this->enctype) ? ( ($this->isMultipart()) ? 'multipart/form-data' : null ) : $this->enctype;
	}

	/**
	 * Disable Elgg's form security
	 */
	public function disableSecurity() {
		$this->disable_security = true;
	}

	/**
	 * Adds a new input
	 * @param str $name Unique identifier
	 * @param str $type I/O view type
	 * @param array $options
	 * @return void 
	 */
	public function registerInput($name, $type = 'text', $required = false, $options = array()) {
		$options['name'] = $name;
		$options['type'] = $type;
		$options['required'] = $required;

		if (!isset($options['priority'])) {
			$options['priority'] = 500 + (sizeof($this->getRawInputs()) * 10 + 10);
		}

		if (!isset($options['fieldset'])) {
			$options['fieldset'] = 'default';
		} else {
			if (!isset($this->fieldsets[$options['fieldset']])) {
				$this->registerFieldset($options['fieldset'], array(
					'priority' => 500 + sizeof($this->getFieldsets()) * 10 + 10
				));
			}
		}
		$this->inputs[$name] = $options;
	}

	/**
	 * Gets an input
	 * @param str $name
	 * @return array
	 */
	public function getInput($name) {
		return $this->inputs[$name];
	}

	/**
	 * Removes an input
	 * @param str $name
	 */
	public function removeInput($name) {
		unset($this->inputs[$name]);
	}

	/**
	 * Set fieldsets
	 * @param array $fieldsets
	 */
	public function setFieldsets($fieldsets) {
		foreach ($fieldsets as $name => $options) {
			$this->registerFieldset($name, $options);
		}
	}

	/**
	 * Get form fieldsets
	 * @return array
	 */
	public function getFieldsets() {
		return $this->fieldsets;
	}

	/**
	 * Adds a new fieldset
	 * @param str $name Unique identifier
	 * @param array $options
	 * @return void
	 */
	public function registerFieldset($name, $options = array()) {
		$options['name'] = $name;
		if (!isset($options['priority'])) {
			$options['priority'] = 500 + sizeof($this->getFieldsets()) * 10 + 10;
		}
		$this->fieldsets[$name] = $options;
	}

	/**
	 * Gets a fieldset
	 * @param str $name
	 * @return array
	 */
	public function getFieldset($name) {
		return $this->fieldsets[$name];
	}

	/**
	 * Add an input to a fieldset
	 * 
	 * @param str $fieldset_name
	 * @param arr $input 
	 */
	public function addInputToFieldset($fieldset_name, $input) {
		$this->fieldsets[$fieldset_name]['inputs'][$input['name']] = $input;
	}

	/**
	 * Removes a fieldset
	 * @param str $name
	 */
	public function removeFieldset($name) {
		unset($this->fieldsets[$name]);
	}

	/**
	 * Set additional object data
	 * @param str $name
	 * @param mixed $value 
	 */
	public function setData($name, $value) {
		$this->data[$name] = $value;
	}

	/**
	 * Get additional object data
	 * @param str $name
	 * @return mixed
	 */
	public function getData($name) {
		return $this->data[$name];
	}

	/**
	 * Set an ElggEntity this form is trying to edit
	 * @param ElggEntity $entity
	 */
	public function setSubjectEntity($entity = null) {
		if (!elgg_instanceof($entity)) {
			return false;
		}
		$this->subject_entity = $entity;
		$this->registerInput('subject_guid', 'hidden', false, array(
			'value' => $entity->guid,
			'fieldset' => 'footer'
		));
	}

	/**
	 * Get an ElggEntity this form is trying to edit
	 * @param ElggEntity $entity
	 */
	public function getSubjectEntity() {
		return $this->subject_entity;
	}

	/**
	 * Describe subject entity to be created
	 * @param str $name
	 * @param str $value
	 */
	public function setSubjectEntityAttr($name, $value) {
		$this->subject_entity_attributes[$name] = $value;
		$this->registerInput($name, 'hidden', false, array(
			'value' => $value,
			'fieldset' => 'footer'
		));
	}

	/**
	 * Get subject entity attributes
	 * @param str $name
	 * @return mixed
	 */
	public function getSubjectEntityAttr($name = null) {
		if ($name) {
			return $this->subject_entity_attributes[$name];
		}
		return $this->subject_entity_attributes;
	}

	/**
	 * Set form title
	 * @param str $title 
	 */
	public function setTitle($title) {
		$this->setData('title', $title);
	}

	/**
	 * Get i18n form title based on the action being performed and the subject entity being edited
	 *
	 * @param ElggEntity $subject_entity
	 * @return str
	 */
	public function getTitle() {

		$title = $this->getData('title');

		if ($title === false) {
			return false;
		} 
		return ($title) ? $title : '';
	}

	/**
	 * Set description
	 * @param str $desc 
	 */
	public function setDescription($desc) {
		$this->setData('description', $desc);
	}

	/**
	 * Get i18n form description based on the action being performed and the subject entity being edited
	 * 
	 * @param ElggEntity $subject_entity
	 * @return str 
	 */
	public function getDescription() {

		$desc = $this->getData('description');

		if ($desc === false) {
			return false;
		} 
		return ($desc) ? $desc : '';
	}

	/**
	 * Set additional key value pairs to be passed to the <form> tag
	 * @param str $key
	 * @param mixed $value 
	 */
	public function setTagAttr($key, $value) {
		$this->data['tag'][$key] = $value;
	}

	/**
	 * Get additional <form> tag attributes
	 * @return mixed
	 */
	public function getTagAttr() {
		return (is_array($this->data['tag'])) ? $this->data['tag'] : array();
	}

	/**
	 * Get form elements
	 * @return array
	 */
	public function getRawInputs() {
		if (empty($this->overwrite_inputs)) {
			return $this->inputs;
		}
		return $this->overwrite_inputs;
	}

	/**
	 * Get overriding form elements
	 *
	 * @return array
	 */
	public function getRawInputsOverwrite() {
		return $this->overwrite_inputs;
	}

	/**
	 * Set a form validation parameter
	 * @param str $name
	 * @param str $value
	 */
	public function setValidationAttr($name, $value) {
		$this->validation[$name] = $value;
	}

	/**
	 * Get form validation parameters
	 * @return mixed
	 */
	public function getValidationAttr($name = null) {
		if ($name) {
			return $this->validation[$name];
		}
		return $this->validation;
	}

	/**
	 * Sort fieldsets, add inputs to each field and sort them by their priority
	 */
	public function sortFieldsets() {
		uasort($this->fieldsets, array('hjDynamicForm', 'compareByWeight'));
		$this->sortInputs();
	}

	/**
	 * Sort inputs by their priority
	 */
	public function sortInputs() {
		$rawInputs = $this->getRawInputs();
		$fieldsets = $this->getFieldsets();

		foreach ($rawInputs as $input) {
			$this->addInputToFieldset($input['fieldset'], $input);
		}

		foreach ($fieldsets as $fieldset) {
			uasort($this->fieldsets[$fieldset['name']]['inputs'], array('hjDynamicForm', 'compareByWeight'));
		}
	}

	/**
	 * Prepare the form for rendering
	 * Allow pluings to overwrite the fieldsets with inputs
	 */
	public function prepareForm() {

		$this->sortFieldsets();

		elgg_trigger_plugin_hook('prepare', "form:{$this->getName()}", null, $this);
	}

	/**
	 * Get params for input/form
	 * @return type
	 */
	public function getFormVars() {
		$extra_tag_attr = $this->getTagAttr();
		$core_tag_attr = array(
			'name' => $this->getName(),
			'action' => $this->getAction(),
			'method' => $this->getMethod(),
			'class' => $this->getClass(),
			'id' => $this->getID(),
			'enctype' => $this->getEnctype(),
			'disable_security' => $this->disable_security,
			'body' => $this->getFormBody()
		);

		return array_merge($extra_tag_attr, $core_tag_attr);
	}

	public function getFormBody() {
		$this->prepareForm();
		return elgg_view($this->getView(), array(
					'name' => $this->getName(),
					'title' => $this->getTitle(),
					'description' => $this->getDescription(),
					'entity' => $this->getSubjectEntity(),
					'fieldsets' => $this->getFieldsets()
				));
	}

	/**
	 * Check if the form contains file input fields
	 * @return bool
	 */
	public function isMultipart() {

		$fieldsets = $this->getFieldsets();

		foreach ($fieldsets as $fieldset) {
			foreach ($fieldset['inputs'] as $input) {
				if ($input['type'] == 'file') {
					return true;
				}
			}
		}
		
		return false;
	}

	/**
	 * Register a message
	 * @param str $msg
	 */
	public function registerStatusMessage($msg) {
		$this->validation['status'][] = $msg;
	}

	/**
	 * Register an error message
	 * @param str $msg
	 */
	public function registerErrorMessage($msg) {
		$this->validation['errors'][] = $msg;
	}

	public static function compareByWeight($a, $b) {

		$a = (isset($a['priority'])) ? $a['priority'] : 500;
		$b = (isset($b['priority'])) ? $b['priority'] : 500;

		return $a > $b;
	}

}
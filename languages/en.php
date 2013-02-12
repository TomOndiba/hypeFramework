<?php

$english = array(

	'hj:framework:input:required' => 'Required Field',

	'hj:framework:error:plugin_order' => '%s can not be activated. Please check your plugin order and ensure that it\'s below hypeFramework in the plugin list',
	'hj:framework:geocode:error' => 'There was a problem with geocoding the location field. The item will not appear on maps or other interfaces that rely on coordinates',
	'hj:framework:list:empty' => 'There are no items to show',
	'hj:framework:list:limit' => 'Show',

	'hj:framework:ajax:loading' => 'Loading...',
	'hj:framework:ajax:saving' => 'Saving...',
	'hj:framework:ajax:deleting' => 'Deleting...',

	'hj:framework:list:empty' => 'There are currently no items to display',

	'hj:framework:sort:ascending' => 'Sort ascending',
	'hj:framework:sort:descending' => 'Sort descending',
	'hj:framework:grid:sort:column' => 'Sort this column',

	'hj:framework:error:cannotcreateentity' => 'Unable to save this item',
	'hj:framework:submit:success' => 'Item was successfully saved',

	'hj:framework:input:validation:success' => 'Complete',
	'hj:framework:input:validation:error:requiredfieldisempty' => '%s can not be empty',
	'hj:framework:form:validation:error' => 'One or more fields are incomplete',
	
	'hj:framework:filter:keywords' => 'Keywords...',

	'hj:framework:edit:object' => 'Edit %s',

	'hj:framework:delete:error:nothjobject' => 'Error. You can delete this item using this action',
	'hj:framework:delete:success' => 'Item successfully removed',
	'hj:framework:delete:error:unknown' => 'An unknown error occured',
	
	'hj:framework:success:accessidset' => 'Visibility level was successfully changed',
	'hj:framework:error:cantsetaccess' => 'Can not change the visibility level',
	
);


add_translation("en", $english);
?>

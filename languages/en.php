<?php

$english = array(

	'options' => 'Options',

	'list_type:list' => 'List',
	'list_type:table' => 'Table',
	'list_type:gallery' => 'Gallery',

	'list_type:list:tooltip' => 'List View',
	'list_type:table:tooltip' => 'Table View',
	'list_type:gallery:tooltip' => 'Gallery View',

	'framework:toggle_filter' => 'Filter',
	'framework:filter:query' => 'Keyword search',
	'framework:filter:search_in' => 'Search scope',
	'framework:filter:order_by' => 'Order by',
	'framework:filter:direction' => 'Sort',
	'framework:filter:limit' => 'Items to show',
	
	'framework:filter:order_by:e.time_created' => 'Time Created',
	'framework:filter:order_by:oe.title' => 'Title',
	'framework:filter:order_by:ue.last_login' => 'Last Login',
	'framework:filter:order_by:ue.name' => 'Name',
	'framework:filter:order_by:ue.username' => 'Username',
	'framework:filter:order_by:ge.name' => 'Group Name',
	'framework:filter:order_by:md.featured_group' => 'Featured',
	'framework:filter:order_by:distance' => 'Distance',

	'framework:filter:search_in:all:tags' => 'Tags',
	'framework:filter:search_in:objects:all' => 'Everywhere',
	'framework:filter:search_in:objects:attributes' => 'Title & description',
	'framework:filter:search_in:objects:tags' => 'Tags',
	'framework:filter:search_in:objects:location' => 'Location',
	'framework:filter:search_in:users:all' => 'Everywhere',
	'framework:filter:search_in:users:attributes' => 'Name, username & email',
	'framework:filter:search_in:users:tags' => 'Tags',
	'framework:filter:search_in:groups:all' => 'Everywhere',
	'framework:filter:search_in:groups:attributes' => 'Name',
	'framework:filter:search_in:groups:tags' => 'Tags',
	'framework:filter:context' => 'Created by',
	
	'framework:filter:fieldset:search' => 'Keyword Search',
	'framework:filter:fieldset:sorting' => 'Sorting',
	'framework:filter:fieldset:context' => 'Context',
	
	'framework:ajax:loading_form' => 'Loading Form...',

	'framework:filter:direction:asc' => 'Ascending',
	'framework:filter:direction:desc' => 'Descending',


    'item:object:hjformsubmission' => 'Form Submission',
	'items:object:hjformsubmission' => 'Form Submissions',

    'item:object:hjfile' => 'File',
	'items:object:hjfile' => 'Files',

	'item:object:hjfilefolder' => 'File Folder',
	'items:object:hjfilefolder' => 'File Folders',

    'item:object:hjportfolio' => 'Portfolio',
	'items:object:hjportfolio' => 'Portfolios',

    'item:object:hjexperience' => 'Work Experience',
	'items:object:hjexperience' => 'Work Experiences',

    'item:object:hjeducation' => 'Education',
	'items:object:hjeducation' => 'Education',

    'item:object:hjskill' => 'Skill',
	'items:object:hjskill' => 'Skills',

    'item:object:hjlanguage' => 'Language',
	'items:object:hjlanguage' => 'Languages',

	'item:object:hjannotation' => 'Comments',
	'items:object:hjannotation' => 'Comments',
    /**
     * River Items
     */
    'river:create:object:hjfile' => '%s added new file %s',
    'river:update:object:hjfile' => '%s updated file %s',
    /**
     * Form Builder Actions
     */
    'hj:formbuilder:form:savesuccess' => 'Your form was successfully saved',
    'hj:formbuilder:form:saveerror' => 'Your form could not be saved',
    'hj:formbuilder:form:delete:success' => 'Form was successfully deleted',
    'hj:formbuilder:form:delete:error' => 'Form could not be deleted',
    'hf:formcheck:fieldmissing' => 'One or more of the required fields is missing',
    'hj:formbuilder:field:savesuccess' => 'Field was successfully saved',
    'hj:formbuilder:field:delete:success' => 'Field was successfully deleted',
    'hj:formbuilder:field:delete:error' => 'There was a problem deleting this form',
    'hj:formbuilder:field:save:success' => 'Field was successfully saved',
    'hj:formbuilder:field:save:error' => 'This field can not be saved',
    'hj:formbuilder:submit:success' => 'Changes submitted',
    'hj:formbuilder:submit:error' => 'This form could not be submitted',
    'hj:formbuilder:formsubmission:subject' => 'New form submission: %s',
    'hj:formbuilder:formsubmission:body' => 'The submission contained the following details: <br /><br /> %s <br /><br />View all submissions for this form at: %s',
    'hj:formbuilder:field:protected' => 'This field is protected and can not be deleted',
    'framework:formcheck:fieldmissing' => 'At least one required field is missing. Please complete all the required fields marked with a red star',
    /**
     * AJAX interface
     */
    'framework:denied' => 'Access Denied',
    'framework:ajax:noentity' => 'There is currently nothing to show',
    'framework:ajax:empty' => 'Sorry, we could not find the information you are looking for',
    /**
     * Actions
     */
    'framework:entity:delete:success' => 'Successfully completed',
    'framework:entity:delete:error' => 'There was an error deleting this instance',
    'framework:widget:add:success' => 'Section added. Please update section settings',
    'framework:widget:add:failure' => "We couldn't add the section",

    /**
     * UI
     */
    'framework:fullview' => 'See more',
    'framework:download' => 'Download',
    'framework:addnew' => 'Add',
    'framework:refresh' => 'Refresh',
    'framework:gallery' => 'Gallery View',
    'framework:gallerytitle' => "Preview",
    'framework:addwidget' => 'Add Section',
    'framework:download' => 'Download',
    'framework:edit' => 'Edit',
    'framework:delete' => 'Delete',
    'framework:email' => 'Send by email',
    'framework:print' => 'Print',
    'framework:pdf' => 'Save as PDF',
    /**
     * Page Handlers
     */
    'framework:denied' => 'Sorry, we can\'t show you this page',
    'framework:print:title' => 'Print: %s',
    'framework:pdf:title' => 'PDF export of %s',
    /**
     * Files
     */
    'framework:newfolder' => 'New Folder',
    'framework:filefolder' => '<b>Folder:</b> %s',
    'framework:filename' => '<b>Filename:</b>  %s',
    'framework:simpletype' => '<b>Type:</b> %s',
    'framework:filesize' => '<b>Size:</b> %s',

    /**
     * hypeJunction
     */
    'framework:disabled' => '%s was disabled to avoid inconsistencies in site operations. Please enable hypeFramework before activating %s',

    /**
     * Forms
     */
	'framework:from:error:fieldexists' => 'Field names must be unique',
	

	// System Messages
	'framework:error:plugin_order' => '%s can not activate. Please check that hypeFramework is enabled and has higher priority position in the plugin list',
	'framework:erorr:cannotcreateentity' => 'An error occurred while trying to create a new entity',

    'hj:label:hjeducation:title' => 'Degree',
    'hj:label:hjeducation:schoolname' => 'School',
    'hj:label:hjeducation:fieldofstudy' => 'Field of Study',
    'hj:label:hjeducation:startdate' => 'Enrollment Date',
    'hj:label:hjeducation:enddate' => 'Graduation Date',
    'hj:label:hjeducation:activities' => 'Activities/Clubs',
    'hj:label:hjeducation:description' => 'Additional Notes',
    'hj:label:hjeducation:access_id' => 'Visibility',

    'hj:label:hjexperience:title' => 'Job Title',
    'hj:label:hjexperience:companyname' => 'Company Name',
    'hj:label:hjexperience:industries' => 'Industry(ies)',
    'hj:label:hjexperience:location' => 'City',
    'hj:label:hjexperience:startdate' => 'Start Date',
    'hj:label:hjexperience:enddate' => 'End Date',
    'hj:label:hjexperience:description' => 'Job Description',
    'hj:label:hjexperience:access_id' => 'Visibility',

    'hj:label:hjsegment:title' => 'Title',
    'hj:label:hjsegment:description' => 'Description',
    'hj:label:hjsegment:access_id' => 'Visibility',

    'framework:embed:options' => 'Embed Options',
    'framework:embed:type' => 'Type:  ',
    'framework:embed:float' => 'Float:  ',
    'framework:embed:float' => 'Link to attach:  ',
    'framework:embed:url' => 'Link to follow:  ',

    'hj:embed:link' => 'Inline Link',
    'hj:embed:small' => 'Small',
    'hj:embed:medium' => 'Medium',
    'hj:embed:large' => 'Large',

    'hj:embed:none' => 'None',
    'hj:embed:right' => 'Right',
    'hj:embed:left' => 'Left',

    /**
     * AJAX / JS
     */
    'framework:processing' => 'Processing...',
    'framework:success' => 'Successfully completed',
    'framework:error' => 'Something went wrong',

	'framework:pagination:loadmore' => 'Show all %s %s',
	'framework:pagination:loadnext' => 'Load more',

	/**
	 * List Types
	 */

	'framework:list:type' => 'List Type',
	'framework:list:list' => 'List',
	'framework:list:gallery' => 'Gallery',
	'framework:list:carousel' => 'Slider',

	'hj:showpanel' => 'Show panel',
	'hj:hidepanel' => 'Hide panel',

	'hj:label:form:new:hypeFramework:fileupload' => 'Upload a file',
	'hj:label:form:edit:hypeFramework:fileupload' => 'Edit a file',
	'hj:label:hjfile:title' => 'Title',
    'hj:label:hjfile:description' => 'Description',
    'hj:label:hjfile:tags' => 'Tags',
    'hj:label:hjfile:filefolder' => 'File Folder',
    'hj:label:hjfile:newfilefolder' => 'New Folder',
    'hj:label:hjfile:fileupload' => 'File to Upload',
    'hj:label:hjfile:access_id' => 'Visibility',

	'framework:error:plugin_order' => '%s can not be activated. Please check your plugin order and ensure that it\'s below hypeFramework in the plugin list',

	'framework:geocode:error' => 'There was a problem with geocoding the location field. The item will not appear on maps or other interfaces that rely on coordinates',

	'framework:list:empty' => 'There are no items to show',

	'range:from' => 'From',
	'range:to' => 'To',
	
);


add_translation("en", $english);
?>

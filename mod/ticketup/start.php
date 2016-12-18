<?php
elgg_register_event_handler('init', 'system', 'ticketup_init');

/**
 * Init
 */
function ticketup_init() {
	/**
	 * Register handler ticketup
	 */ 
	elgg_register_page_handler('ticketup', 'ticketup_page_handler');

	// override the default url to view a blog object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'ticketup_set_url');

	// Register for search.
	elgg_register_entity_type('object', 'ticketup');

	// add a site navigation item
	$item = new ElggMenuItem('ticketup', elgg_echo('ticketup:new'), 'blog/new');
	elgg_register_menu_item('site', $item);

	/**
	 * Register actions
	 */ 
	$action_path = dirname(__FILE__) . '/actions/';
	elgg_register_action('uploadTicket', $action_path . 'uploadTicket.php');
}

/**
 * @param  [type]
 * @return [type]
 */
function ticketup_page_handler($page){
	elgg_load_library('elgg:ticketup');
}


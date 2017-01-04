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

	// register a library of helper functions
	elgg_register_library('elgg:ticketup', __DIR__ . '/lib/ticketup.php');

	// override the default url to view a blog object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'ticketup_set_url');

	// Register for search.
	elgg_register_entity_type('object', 'ticketup');

	//Se registra la clase para el ticket
	if (!update_subtype('object', 'ticketup', 'ElggTicket')) {
		add_subtype('object', 'ticketup', 'ElggTicket');
	}
	//Se registra la clase para el producto
	if (!update_subtype('object', 'product', 'ElggProduct')) {
		add_subtype('object', 'product', 'ElggProduct');
	}

	// add a site navigation item
	$item = new ElggMenuItem('ticketup', elgg_echo('ticketup:new'), 'ticketup/add');
	elgg_register_menu_item('site', $item);

	/**
	 * Register actions
	 */ 
	$action_path = __DIR__ . '/actions/ticketup';
	elgg_register_action("ticketup/upload", "$action_path/upload.php");

	/**
	 * Register js
	 * @var [type]
	 */
	elgg_register_simplecache_view('js/ticketup/jquery.dataTables.min.js');
	elgg_register_simplecache_view('js/ticketup/uploadTicket.js');

	elgg_require_js('ticketup/jquery.dataTables.min');
	elgg_require_js('ticketup/uploadTicket');

	/**
	 * Register css
	 */
	elgg_require_css('css/ticketup/jquery.dataTables.min');
	elgg_require_css('css/ticketup/style');


	//Se quita el acceso a la actividad y a los ficheros ya que son las dos plugins activados
	elgg_unregister_menu_item('site','file');
	elgg_unregister_menu_item('site','activity');
	elgg_unregister_page_handler('file');
}

/**
 * @param  [type]
 * @return [type]
 */
function ticketup_page_handler($page){
	$page_type = $page[0];
	switch ($page_type) {
		case 'all':
			file_register_toggle();
			echo elgg_view_resource('ticketup/world');
			break;
		case 'add':
			echo elgg_view_resource('ticketup/upload');
			break;
		case 'view':
			echo elgg_view_resource('ticketup/view', [
				'guid' => $page[1],
			]);
			break;
	}
}

/**
 * Populates the ->getUrl() method for file objects
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string File URL
 */
function ticketup_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	
	if (elgg_instanceof($entity, 'object', 'ticketup')) {
		return "ticketup/view/" . $entity->getGUID();
	}
}


/**
 * Funcion para registrar un css
 * @param  [type] $view [description]
 * @return [type]       [description]
 */
function elgg_require_css($view) {
    $key = "elgg_require_css:$view";
    elgg_register_css($key, elgg_get_simplecache_url($view));
    elgg_load_css($key);
}

/**
 * Funcion para eliminar un css
 * @param  [type] $view [description]
 * @return [type]       [description]
 */
function elgg_unrequire_css($view) {
    $key = "elgg_require_css:$view";
    elgg_unregister_css($key);
}
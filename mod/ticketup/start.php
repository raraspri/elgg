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

	if(elgg_is_active_plugin('web_services')){
		// Test get info shop 
		elgg_ws_expose_function(
			"getInfoTicket",
			"get_info_ticket",
			array(
				'cod_ticket' => array ('type' => 'string')
			),
			elgg_echo('ticketp:webservice'),
			'GET',
			false,
			false
		);
	}
}

function get_info_ticket(){
	return "MERCADONA:2016/03/21:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:LECHE:3:10.25:";
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
		case 'alg':
			$rep = obtenerRepeticiones('LECHE');
			algorithms($rep);
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

function obtenerRepeticiones($product){
	//Se obtienen todos los productos con el nombre $product
	$products = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'product',
		'metadata_name' => 'name',
		'metadata_value' => $product,
		'limit' => false,
	));

	//Se obtienen las fechas de cada producto
	$fechas = array();
	foreach ($products as $obj) {

		$idticket = $obj->getIdTicket();
		$ticket = new ElggTicketup($idticket);

		if($ticket instanceof ElggTicketup){
			$fechas[] = $ticket->getDate();
		}
	}
	
	//Se ordenan las fechas de menor a mayor
	sort($fechas);

	//Se obtienen los intervalos entre las fechas
	$intervalos = array();
	for ($i=0; $i < count($fechas)-1; $i++) { 
		$intervalos[] = ($fechas[$i+1]-$fechas[$i])/86400;
	}

	var_dump(algorithms($intervalos));
	exit;
}

/**
 * [algorithms description]
 * @param  [type] $buy_int [description]
 * @return [type]          [description]
 */
function algorithms($buy_int){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	// From DB-----
	$user_tickets=5;

	//-------
	$times_bought=count($buy_int);
	// Params -------
	$gauss_int=20;
	$min_tickets=3;
	$min_product=3;
	$D_rep=1;
	//--------

	if ($user_tickets<$min_tickets or $times_bought<$min_product) {
		exit('Not enough data');
	}

	$distr = array_count_values($buy_int); 
	$distr[0]=0;
	$distr[max(array_keys($distr))+3]=0;
	ksort($distr);

	$x0=0;
	$y0=0;
	$sign=0;

	$distr_day = array_keys($distr); 
	$distr_rep = array_values($distr);  

	$x0=0; 
	$y0=0; 
	$sign=0;  
	for ($i=0; $i < count($distr_day)-1; $i++) {   	
		if ($sign!==sign($distr_rep[$i+1]-$distr_rep[$i]) and sign($distr_rep[$i+1]-$distr_rep[$i])!==1) { 		
			$mode_day[]=$distr_day[$i]; 		
			$mode_rep[]=$distr_rep[$i]; 	
		} 	
		$sign=sign($distr_rep[$i+1]-$distr_rep[$i]);  
	}

	
	$y_max=max($mode_rep);

	foreach ($mode_rep as $x => $y) {

		if ($y<$y_max-$D_rep) {

			unset($mode_day[$x]);
			unset($mode_rep[$x]);
			continue;
		}
	}

	$next_day=min($mode_day);

	return $next_day;
}

/**
 * [sign description]
 * @param  [type] $n [description]
 * @return [type]    [description]
 */
function sign($n) {     
	return ($n > 0) - ($n < 0); 
}
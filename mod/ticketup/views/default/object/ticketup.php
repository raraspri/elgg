<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$full = elgg_extract('full_view', $vars, FALSE);
$ticketup = elgg_extract('entity', $vars, FALSE);

$ticketup = new ElggTicketup($ticketup->guid);

if (!$ticketup) {
	return TRUE;
}

if ($ticketup->getPhoto()) {
	$fileTicketup = get_entity($ticketup->getPhoto());
}

if ($full) {		
	if($fileTicketup){
		$file_icon = elgg_view_entity_icon($fileTicketup, 'medium', array('href' => $ticketup->getURL()));
	}
	

	$body = elgg_view_resource('ticketup/table', [
													'shop' => $ticketup->getShop(), 
													'date' => $ticketup->getDate(), 
													'products' => $ticketup->getProducts(), 
												]);

	$summary = $ticketup->getShop() ." - ".$ticketup->getDate();

	echo elgg_view('object/elements/full', array(
		'entity' => $ticketup,
		'icon' => $file_icon,
		'summary' => $summary,
		'body' => $body,
	));

} else {
	// brief view
	$excerpt = $ticketup->getShop() ." - ".$ticketup->getDate();;

	if($fileTicketup){
		$file_icon = elgg_view_entity_icon($fileTicketup, 'small', ['href' => $ticketup->getURL()]);
	}

	$params = array(
		'entity' => $ticketup,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($file_icon, $list_body);
}

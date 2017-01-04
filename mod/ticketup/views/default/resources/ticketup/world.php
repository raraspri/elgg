<?php
/**
 * All ticketups
 *
 * @package ElggTicketup
 */

elgg_push_breadcrumb(elgg_echo('ticketup'));

$title = elgg_echo('ticketup:all');

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'ticketup',
	'full_view' => false,
	'no_results' => elgg_echo("ticketup:none"),
	'preload_owners' => true,
	'preload_containers' => true,
	'distinct' => false,
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
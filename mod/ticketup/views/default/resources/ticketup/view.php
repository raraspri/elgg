<?php
/**
 * View a ticketup
 *
 * @package ElggTicketup
 */

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'ticketup');

$ticketup = get_entity($guid);

elgg_group_gatekeeper();

elgg_push_breadcrumb(elgg_echo('ticketup'), 'ticketup/add');

$content = elgg_view_entity($ticketup, array('full_view' => true));

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);

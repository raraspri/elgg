<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

elgg_load_library('elgg:ticketup');

$owner = elgg_get_page_owner_entity();

elgg_gatekeeper();
elgg_group_gatekeeper();

$title = elgg_echo('ticketup:add');

// set up breadcrumbs
elgg_push_breadcrumb(elgg_echo('ticketup'), "ticketup/add");

// create form
$form_vars = array('enctype' => 'multipart/form-data');
$body_vars = ticketup_prepare_form_vars();
$content = elgg_view_form('ticketup/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
));

echo elgg_view_page($title, $body);
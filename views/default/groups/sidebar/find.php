<?php

$title = elgg_echo('search') . ' ' . elgg_echo('groups');

$body = elgg_view_form('groups/find', array(
	'action' => 'search',
	'method' => 'get',
	'disable_security' => true,
));

echo elgg_view_module('aside', $title, $body);

<?php

echo elgg_view('input/text', [
	'name' => 'q',
	'placeholder' => 'Group Name',
]);

echo elgg_view('input/hidden', [
	'name' => 'entity_type',
	'value' => 'group',
]);
echo elgg_view('input/hidden', [
	'name' => 'search_type',
	'value' => 'entities',
]);

echo elgg_view('input/submit', [
	'value' => elgg_echo('search:go'),
	'hidden' => true,
]);

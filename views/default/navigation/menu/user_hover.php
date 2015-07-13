<?php
/**
 * User hover menu
 *
 * Register for the 'register', 'menu:user_hover' plugin hook to add to the user
 * hover menu. There are three sections: action, default, and admin.
 *
 * @uses $vars['menu']      Menu array provided by elgg_view_menu()
 */

$user = $vars['entity'];
$actions = elgg_extract('action', $vars['menu'], null);
$main = elgg_extract('default', $vars['menu'], null);
$admin = elgg_extract('admin', $vars['menu'], null);

echo '<ul class="elgg-menu elgg-menu-hover">';

$subject = elgg_view('navigation/menu/user_hover/subject', [
	'user' => $user,
]);
echo "<li>$subject</li>";

// actions
if (elgg_is_logged_in() && $actions) {
	echo '<li>';
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => "elgg-menu elgg-menu-hover-actions",
		'items' => $actions,
	));
	echo '</li>';
}

// main
if ($main) {
	echo '<li>';
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu elgg-menu-hover-default',
		'items' => $main,
	));
	
	echo '</li>';
}

// admin
if (elgg_is_admin_logged_in() && $admin) {
	echo '<li>';
	
	echo elgg_view('navigation/menu/elements/section', array(
		'class' => 'elgg-menu elgg-menu-hover-admin',
		'items' => $admin,
	));
	
	echo '</li>';
}

echo '</ul>';

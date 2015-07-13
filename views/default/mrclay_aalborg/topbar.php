<div class="mrclay-aalborg-account">
	<?php
	if (elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
		echo elgg_view_menu('topbar', [
			'sort_by' => 'priority',
			'class' => 'elgg-menu-hz',
		]);
	} else {
		$body = elgg_view_form('login', array(), array('returntoreferer' => TRUE));
		echo elgg_view('output/url', array(
			'href' => 'login#login-dropdown-box',
			'rel' => 'popup',
			'class' => 'elgg-button elgg-button-dropdown',
			'text' => elgg_echo('login'),
		));
		echo elgg_view_module('dropdown', '', $body, array('id' => 'login-dropdown-box'));
	}
	?>
</div>

<div class="mrclay-aalborg-account">
	<?php
	if (elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
		/*
		?>
		<a class="elgg-button-nav" rel="toggle" data-toggle-selector=".elgg-menu-topbar" href="#">
			<?= elgg_view('output/img', array(
				'src' => $user->getIconURL('topbar'),
				'alt' => $user->name,
				'title' => elgg_echo('profile'),
				'class' => 'elgg-border-plain elgg-transition',
			)) ?>
		</a>
		<?php
		*/
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

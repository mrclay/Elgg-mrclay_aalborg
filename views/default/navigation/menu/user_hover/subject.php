<?php

$user = $vars['user'];
/* @var ElggUser $user */

// name and username
if (!elgg_in_context('mrclay_aalborg_topbar')) {
	echo elgg_view('output/url', array(
		'href' => $user->getURL(),
		'text' => "<span class=\"elgg-heading-basic\">$user->name</span>&#64;$user->username",
		'is_trusted' => true,
	));
	return;
}

$avatar_edit_text = $user->icontime ? elgg_echo('avatar:edit') : elgg_echo('avatar:create');

$avatar_text = elgg_view('output/img', array(
	'src' => $user->getIconURL('medium'),
	'alt' => '',
	'style' => 'min-height:100px; min-width:100px',
));
$avatar_text .= "<span>$avatar_edit_text</span>";

$avatar = elgg_view('output/url', [
	'text' => $avatar_text,
	'href' => "avatar/edit/{$user->username}",
	'class' => 'mrclay-aalborg-avatar',
]);

$heading = "<span class=\"elgg-heading-basic\">$user->name</span>@$user->username";

$profile = elgg_view('output/url', array(
	'href' => $user->getURL(),
	'text' => elgg_echo('profile'),
	'is_trusted' => true,
));

$settings = elgg_view('output/url', [
	'text' => elgg_echo('settings'),
	'href' => "settings/user/{$user->username}",
]);

ob_start();
?>
	<?= $heading ?>
	<ul>
		<li><?= $profile ?></li>
		<li><?= $settings ?></li>
	</ul>
<?php
$body = ob_get_clean();

echo elgg_view_image_block($avatar, $body);

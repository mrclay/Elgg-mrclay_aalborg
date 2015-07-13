<?php

if (elgg_is_logged_in()) {
	return;
}

?>
<p class="mrclay-aalborg-login"><?= elgg_echo('mrclay_aalborg:login_to_comment'); ?></p>

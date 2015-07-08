<?php

namespace MrClay\AalborgExtras;

use UFCOE\Elgg\MenuList;

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init_late', 1000);

function init_late() {
	elgg_extend_view('css/elgg', 'css/mrclay_aalborg');

	elgg_register_plugin_hook_handler('prepare', 'menu:topbar', 'MrClay\AalborgExtras\Topbar::prepareMenu', 1000);
	elgg_register_plugin_hook_handler('prepare', 'menu:user_hover', 'MrClay\AalborgExtras\UserHover::prepareMenu', 1000);
	elgg_register_plugin_hook_handler('register', 'menu:page', 'MrClay\AalborgExtras\Files::registerPageMenu', 1000);
	elgg_register_plugin_hook_handler('register', 'menu:extras', 'MrClay\AalborgExtras\Files::registerExtrasMenu', 1000);
	elgg_register_plugin_hook_handler('route', 'file', 'MrClay\AalborgExtras\Files::handleFileRoute', 1000);

	elgg_unextend_view('profile/status', 'thewire/profile_status');

	elgg_require_js('mrclay_aalborg');
}


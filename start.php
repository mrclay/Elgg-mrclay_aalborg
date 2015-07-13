<?php

namespace MrClay\AalborgExtras;

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init_late', 1000);

function init_late() {
	elgg_extend_view('css/elgg', 'css/mrclay_aalborg');

	elgg_register_css('fonts.Open Sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,300&subset=latin,latin-ext');
	elgg_load_css('fonts.Open Sans');

	elgg_require_js('mrclay_aalborg');

	// add a topbar in the header
	// TODO leave topbar in place and change with CSS
	elgg_extend_view('page/elements/header', 'mrclay_aalborg/topbar', 0);

	elgg_extend_view('forms/comment/save', 'mrclay_aalborg/comment_save', 0);

	// topbar menu edits
	elgg_register_plugin_hook_handler('prepare', 'menu:topbar', 'MrClay\AalborgExtras\Topbar::prepareMenu', 1000);

	// user hover customizations
	elgg_register_plugin_hook_handler('prepare', 'menu:user_hover', 'MrClay\AalborgExtras\UserHover::prepareMenu', 1000);

	// https://github.com/Elgg/Elgg/issues/8718
	elgg_register_plugin_hook_handler('route', 'file', 'MrClay\AalborgExtras\Files::handleFileRoute', 1000);
	elgg_register_plugin_hook_handler('register', 'menu:page', 'MrClay\AalborgExtras\Files::registerPageMenu', 1000);
	elgg_register_plugin_hook_handler('register', 'menu:extras', 'MrClay\AalborgExtras\Files::registerExtrasMenu', 1000);

	// Replace "Navigation" with "Pages"
	// TODO cleanup
	elgg_register_plugin_hook_handler('view', 'pages/sidebar/navigation', function($h, $t, $v, $p) {
		$v = preg_replace('~<h3>.*?</h3>~', '<h3>' . elgg_echo('pages') . '</h3>', $v, 1);
		return $v;
	});

	// https://github.com/Elgg/Elgg/issues/8697
	elgg_unextend_view('profile/status', 'thewire/profile_status');

	// https://github.com/Elgg/Elgg/issues/8628
	if (version_compare(elgg_get_version(), '2.0', '>=')) {
		// https://github.com/Elgg/Elgg/issues/8628
		elgg_unregister_menu_item('extras', 'report_this');

		if (elgg_is_logged_in()) {
			elgg_register_menu_item('extras', array(
				'name' => 'report_this',
				'href' => 'reportedcontent/add',
				'title' => elgg_echo('reportedcontent:this:tooltip'),
				'text' => elgg_view_icon('exclamation-triangle'),
				'priority' => 500,
				'section' => 'default',
				'link_class' => 'elgg-lightbox',
			));
		}
	}

	$path = substr(current_page_url(), strlen(elgg_get_site_url()));

	// remove duplicate title from page view
	// TODO cleanup
	if (preg_match('~^pages/view/(\d+)~', $path, $m)) {
		$guid = (int)$m[1];

		// https://github.com/Elgg/Elgg/issues/8723
		elgg_register_plugin_hook_handler('view_vars', 'object/elements/summary', function ($h, $t, $vars, $p) use ($guid) {
			if (empty($vars['entity'])) {
				return;
			}
			$entity = $vars['entity'];
			/* @var \ElggEntity $entity */

			// make sure this is the expected entity
			if ($entity->guid !== $guid) {
				return;
			}
			$vars['title'] = false;
			return $vars;
		});

		// https://github.com/Elgg/Elgg/issues/8722
		elgg_register_plugin_hook_handler('view_vars', 'object/elements/full', function ($h, $t, $vars, $p) use ($guid) {
			// make sure this is the expected entity
			if (empty($vars['entity'])) {
				return;
			}
			$entity = $vars['entity'];
			/* @var \ElggEntity $entity */

			if ($entity->guid !== $guid) {
				return;
			}
			$vars['icon'] = elgg_view_entity_icon($entity->getOwnerEntity(), 'tiny');
			return $vars;
		});
	}

//	// add resources view classes to BODY
//	elgg_register_plugin_hook_handler('view_vars', 'all', function ($h, $view, $vars, $p) {
//		if (0 !== strpos($view, 'resources/')) {
//			return;
//		}
//
//		$classes = [];
//
//		$classes[] = 'elgg-' . preg_replace('~[^a-zA-Z0-9]+~', '-', $view);
//
//		if (is_array($vars)) {
//			foreach ($vars as $key => $value) {
//				if (is_string($value)
//						&& preg_match('~^[a-zA-Z0-9_]+$~', $key)
//						&& preg_match('~^[a-zA-Z0-9_]+$~', $key)) {
//					$classes[] = "elgg-resource-vars-$key-$value";
//				}
//			}
//		}
//
//		$classes_adder = function ($h, $t, $vars, $p) use ($classes) {
//			$body_attrs = (array)elgg_extract('body_attrs', $vars, []);
//			$body_classes = (array)elgg_extract('class', $body_attrs, []);
//
//			array_splice($body_classes, count($body_classes), 0, $classes);
//			$vars['body_attrs']['class'] = $body_classes;
//
//			return $vars;
//		};
//		elgg_register_plugin_hook_handler('view_vars', 'page/elements/html', $classes_adder);
//	});
}

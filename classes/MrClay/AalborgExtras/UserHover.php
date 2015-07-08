<?php
namespace MrClay\AalborgExtras;

use UFCOE\Elgg\MenuList;

class UserHover {
	static public function prepareMenu($h, $t, $v, $p) {
		if (!elgg_in_context('mrclay_aalborg_topbar')) {
			return;
		}
		$action_section = new MenuList(elgg_extract('action', $v, []));

		$user = $p['entity'];
		/* @var \ElggUser $user */

		if (elgg_is_active_plugin('notifications')) {
			$item = \ElggMenuItem::factory(array(
				'name' => '2_a_user_notify',
				'text' => elgg_echo('notifications:subscriptions:changesettings'),
				'href' => "notifications/personal/{$user->username}",
				'section' => "notifications",
			));
			$action_section->push($item);

			if (elgg_is_active_plugin('groups')) {
				$item = \ElggMenuItem::factory(array(
					'name' => '2_group_notify',
					'text' => elgg_echo('notifications:subscriptions:changesettings:groups'),
					'href' => "notifications/group/{$user->username}",
					'section' => "notifications",
				));
				$action_section->push($item);
			}
		}

		$item = \ElggMenuItem::factory(array(
			'name' => 'settings',
			'text' => elgg_echo('settings'),
			'href' => "settings/user/{$user->username}",
		));
		$action_section->move($item, 0);

		$item = \ElggMenuItem::factory(array(
			'name' => 'logout',
			'text' => elgg_echo('logout'),
			'href' => elgg_add_action_tokens_to_url("action/logout"),
		));
		$action_section->move($item, 0);

		$v['action'] = $action_section->getItems();
		return $v;
	}
}

<?php
namespace MrClay\AalborgExtras;

use UFCOE\Elgg\MenuList;

class Topbar {
	public static function prepareMenu($h, $t, $v, $p) {
		$default = new MenuList(elgg_extract('default', $v, []));
		$alt = new MenuList(elgg_extract('alt', $v, []));

		// dump alt items into default
		$default->appendList($alt);

		$avatar = $default->get('profile');
		$account = $default->get('account');

		if ($avatar && $account) {
			$user = elgg_get_logged_in_user_entity();

			// copy account children under avatar
			$children = new MenuList($account->getChildren());

			// copy admin out
			$admin = $children->remove('administration');

			$url = $avatar->getHref();
			$profile = new \ElggMenuItem('view-profile', elgg_echo('profile'), $url);
			$children->move($profile, 0);

			$avatar->setHref(null);
			elgg_push_context('mrclay_aalborg_topbar');
			$avatar->setText(elgg_view_entity_icon($user, 'tiny'));
			elgg_pop_context();

			$default->remove($account);
			$default->push($avatar);

			if ($admin) {
				$admin->setTooltip(elgg_echo('admin'));
				$admin->setText(elgg_view_icon('settings-alt'));
				$default->move($admin, 0);
			}
		}

		return [
			'default' => $default->getItems(),
		];
	}
}

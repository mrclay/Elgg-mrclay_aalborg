<?php
namespace MrClay\AalborgExtras;

use UFCOE\Elgg\MenuList;

class Files {
	private static $add_toggle = false;

	public static function handleFileRoute($h, $t, $v, $p) {
		$segment_two = elgg_extract(0, $v['segments'], '');
		$show_toggle = array(
			'owner',
			'friends',
			'search',
			'group',
			'all',
			'',
		);
		if (in_array($segment_two, $show_toggle)) {
			self::$add_toggle = true;
		}
	}

	public static function registerPageMenu($h, $t, $v, $p) {
		if (!self::$add_toggle) {
			return;
		}

		$url = elgg_http_remove_url_query_element(current_page_url(), 'list_type');

		if (get_input('list_type', 'list') == 'list') {
			$list_type = "gallery";
			$icon = elgg_view_icon('grid');
		} else {
			$list_type = "list";
			$icon = elgg_view_icon('list');
		}

		if (substr_count($url, '?')) {
			$url .= "&list_type=" . $list_type;
		} else {
			$url .= "?list_type=" . $list_type;
		}

		$item = \ElggMenuItem::factory(array(
			'name' => 'file_list',
			'text' => elgg_echo("file:list:$list_type") . " $icon",
			'href' => $url,
			'priority' => 1000,
			'section' => 'view_toggle',
		));
		$v[] = $item;

		return $v;
	}

	public static function registerExtrasMenu($h, $t, $v, $p) {
		$all = new MenuList($v);
		$all->remove('file_list');
		return $all->getItems();
	}
}
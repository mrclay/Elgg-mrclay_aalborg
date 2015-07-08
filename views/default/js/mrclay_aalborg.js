define('mrclay_aalborg', function(require){
	var $ = require('jquery');

	$(document).on('click', '.elgg-menu-item-profile > a', function () {
		$('.elgg-menu-item-profile').toggleClass('opened');
		return false;
	});

	$(document).on('click', '.elgg-menu-topbar .elgg-menu-item-administration', function () {
		var $gear = $('.developers-gear span');
		if ($gear.length) {
			$gear.trigger('click');
			return false;
		}
	});
});

$(document).ready(function() { // hover for click sort

	var _thumbs = $('#click_sort ul li');
	_thumbs.mouseover(itemhover);

	function itemhover() {
		$(this).siblings().find('.click_sort_float').slideUp('fast');
		$(this).find('.click_sort_float').slideDown('fast');
		_thumbs.removeClass("current");
		$(this).addClass("current");
	}

});
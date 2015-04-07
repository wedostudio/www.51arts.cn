// for product show img
$(document).ready(function() {

	var _thumbs = $('#product_imgs #product_img_lit ul li');
	var _mainshow = $('#product_imgs #product_img_big');
	var slide = function(opts) {
		if (opts) {
			_current = opts.current || 0;
		} else {
			_current = 0;
		}
		_mainshow.find('img').attr('src', _thumbs.eq(_current).find('img').attr('src'));
		_thumbs.removeClass("current").eq(_current).addClass("current");
	};

	var itemMouseOver = function(target, items) {
		var i = $.inArray(target, items);
		slide({
			current: i
		});
	};
	_thumbs.mouseover(function() {
		if ($(this).attr('class') != 'current') {
			itemMouseOver(this, _thumbs);
		}
	});
	slide();
});


// for product show classify selected
$(document).ready(function() {
	var _thumbs = $('#product_classify ul li');
	var selected = $('#classify_selected');

	_thumbs.click(function() {
		var i = $.inArray(this, _thumbs);
		selected.val(i);
		_thumbs.removeClass("current").eq(i).addClass("current");
	});
	_thumbs.eq(0).click();
});

// for product plate
$(document).ready(function() {
	var _navs = $('#product_nav ul li');
	var _plates = $('#product_plate > div');

	_navs.click(function() {
		var i = $.inArray(this, _navs);
		_navs.removeClass('current').eq(i).addClass('current');
		_plates.removeClass('current').eq(i).addClass('current');
	});

	_navs.eq(0).click();
});
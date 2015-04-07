// for activity

// for round show box (button inside)
jQuery.showbox_in = function(box) {
	var defaultOpts = {
		interval: 4000,
		fadeInTime: 300,
		fadeOutTime: 200
	};
	var _thumbs = $(box + " ul.slide-li li");
	var _bodies = $(box + " ul.slide-pic li");
	var _count = _thumbs.length;
	var _current = 0;
	var _intervalID = null;
	var stop = function() {
		window.clearInterval(_intervalID);
	};
	var slide = function(opts) {
		if (opts) {
			_current = opts.current || 0;
		} else {
			_current = (_current >= (_count - 1)) ? 0 : (++_current);
		};
		_bodies.filter(":visible").fadeOut(defaultOpts.fadeOutTime, function() {
			_bodies.eq(_current).fadeIn(defaultOpts.fadeInTime);
			_bodies.removeClass("current").eq(_current).addClass("current");
		});
		_thumbs.removeClass("current").eq(_current).addClass("current");
	};
	var go = function() {
		stop();
		_intervalID = window.setInterval(function() {
			slide();
		}, defaultOpts.interval);
	};
	var itemMouseOver = function(target, items) {
		stop();
		var i = $.inArray(target, items);
		slide({
			current: i
		});
	};
	_thumbs.hover(function() {
		if ($(this).attr('class') != 'current') {
			itemMouseOver(this, _thumbs);
		} else {
			stop();
		}
	}, go);
	_bodies.hover(stop, go);
	go();
};

// for round show box (button outside)
jQuery.showbox_out = function(box) {
	var defaultOpts = {
		interval: 4000,
		fadeInTime: 300,
		fadeOutTime: 200
	};
	var _thumbs = $(box + " ul.slide-li li");
	var _bodies = $(box + " .slide-pic");
	var _count = _thumbs.length;
	var _current = 0;
	var _intervalID = null;
	var stop = function() {
		window.clearInterval(_intervalID);
	};
	var slide = function(opts) {
		if (opts) {
			_current = opts.current || 0;
		} else {
			_current = (_current >= (_count - 1)) ? 0 : (++_current);
		};
		_bodies.find('img').fadeOut(defaultOpts.fadeOutTime, function() {
			$(this).attr('src', _thumbs.eq(_current).find('img').attr('src'));
			$(this).siblings('p').html(_thumbs.eq(_current).find('p').html());
			$(this).fadeIn(defaultOpts.fadeInTime);

		});
		_thumbs.removeClass("current").eq(_current).addClass("current");
	};
	var go = function() {
		stop();
		_intervalID = window.setInterval(function() {
			slide();
		}, defaultOpts.interval);
	};
	var itemMouseOver = function(target, items) {
		stop();
		var i = $.inArray(target, items);
		slide({
			current: i
		});
	};
	_thumbs.hover(function() {

		if ($(this).attr('class') != 'current') {
			itemMouseOver(this, _thumbs);
		} else {
			stop();
		}
	}, go);
	_bodies.hover(stop, go);
	slide({
		current: 0
	});
	go();
};


// show box
$(document).ready(function() {
	$.showbox_in("#act"); // for activity
	$.showbox_out("#hot_sale_showbox"); // for hot sale
	$.showbox_out("#new_item_showbox"); // for new item
});
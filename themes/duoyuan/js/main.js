$(document).ready(function() { // for banner and fullwidth to adjust width
	$('#wrap').css({
		'min-width': $('#wrap').width() + 'px'
	});
});


$(document).ready(function() { // for artist signed
	var floathtml = "<a id=\"artist_signed_float\" href=\"#\"></a>";
	$("#artist_signed").html(floathtml);
	var lastRmenuStatus = false;
	$(window).scroll(function() {
		var _top = $(window).scrollTop();
		if (_top > 200) {
			$("#artist_signed_float").data("expanded", true);
		} else {
			$("#artist_signed_float").data("expanded", false);
		}
		if ($("#artist_signed_float").data("expanded") != lastRmenuStatus) {
			lastRmenuStatus = $("#artist_signed_float").data("expanded");
			if (lastRmenuStatus) {
				$("#artist_signed_float").slideDown();
			} else {
				$("#artist_signed_float").slideUp();
			}
		}
	});
});


// for artinfo slide
$(document).ready(function() {
	var idx = 0;
	var swtimer;
	var interval = 5000;
	var slideinterval = 1200;
	var round_size = 3;
	var size = Math.ceil($('#artinfo_list ul li').length / round_size);

	$('#artinfo_list ul').hover(hover_timer, leave_timer).trigger('mouseleave');;
	$('#artinfo').siblings('#artinfo_pre, #artinfo_next').hover(hover_timer, leave_timer);

	$('#artinfo').siblings('#artinfo_pre, #artinfo_next').bind('click', function() {
		var id = this.id;
		if (id == 'artinfo_pre') {
			idx--;
			if (idx < 0) idx = size - 1;
		} else {
			idx++;
			if (idx >= size) idx = 0;
		}
		showpic(idx);
	});

	function timer_showpic() {
		idx++;
		if (idx >= size) {
			idx = 0;
		}
		showpic(idx);
	}

	function showpic(idx) {
		var perwidth = $('#artinfo').width() + parseInt($('#artinfo_list ul li').css('margin-right'));
		$('#artinfo_list ul li').animate({
			left: -perwidth * idx
		}, slideinterval);
	}

	function hover_timer() {
		if (swtimer)
			clearInterval(swtimer);
	}

	function leave_timer() {
		swtimer = setInterval(timer_showpic, interval);
	}
});



$(document).ready(function() { // hover for items

	$('table.show_iteminfo td').hover(itemhover, itemleave);

	function itemhover() {
		$(this).find('img').css({
			'filter': 'alpha(opacity=20)',
			'opacity': '0.2'
		});
		$(this).children('p').css({
			'display': 'block'
		});
	}

	function itemleave() {

		$(this).find('img').css({
			'filter': 'alpha(opacity=100)',
			'opacity': '1.0'
		});
		$(this).children('p').css({
			'display': 'none'
		});
	}
});


$(document).ready(function() { // hover for artists 

	$('#artcircle li a').hover(artistshover, artistsleave);

	function artistshover() {
		$(this).children('p').css({
			'display': 'block'
		});
		$(this).children('span').css({
			'display': 'block'
		});
	}

	function artistsleave() {
		$(this).children('p').css({
			'display': 'none'
		});
		$(this).children('span').css({
			'display': 'none'
		});
	}
});
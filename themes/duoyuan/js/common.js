//for navigation
$(document).ready(function() {
	var $navs = $("#nav .navs");
	$navs.mouseover(function() {
		var i = $.inArray(this, $navs) + 1;
		$('#subNav_' + i).css({
			'display': 'block'
		});
	}).mouseout(function() {
		var i = $.inArray(this, $navs) + 1;
		$('#subNav_' + i).css({
			'display': 'none'
		});
	});
});

//  for search
$(document).ready(function() {
	$('#keyword').focus(function() {
		$('#keyword_def').css({
			'display': 'none'
		});
	}).blur(function() {
		if (this.value.length == 0)
			$('#keyword_def').css({
				'display': 'inline'
			});
	});

	$.divselect("#divselect", "#inputselect");
});


// for shopping cart count
$(document).ready(function() {
	var cart_timer;
	var check_interval = 1000;
	cart_timer = setInterval(checking_cart_cnt, check_interval);

	function checking_cart_cnt() {
		var cnt = parseInt($('#nav .nav_cart_cnt').text());
		if (!isNaN(cnt) && cnt != 0) {
			$('#nav .nav_cart_cnt').css({
				'display': 'block'
			});
		}
	}
});


// for text overflow dotdotdot
$(document).ready(function() {
	$(".text_overflow_ddd").each(function(i) {
		var outer_h = $(this).height();
		var $p = $("p", $(this)).eq(0);
		while ($p.outerHeight() > outer_h) {
			$p.text($p.text().replace(/(\s)*([a-zA-Z0-9]+|\W)(\.\.\.)?$/, "..."));
		};
	});
});


// footer shotcut	
$(document).ready(function() {
	$('#footer .ft_shot_2').click(favorites);
});
$(document).ready(function() { // hover for sale rank

	$('#salerank .salerank_item td').hover(itemhover, itemleave);

	function itemhover() {
		$(this).children('img').css({
			'filter': 'alpha(opacity=50)',
			'opacity': '0.5'
		});
		$(this).children('a').css({
			'display': 'block'
		});
	}

	function itemleave() {

		$(this).children('img').css({
			'filter': 'alpha(opacity=100)',
			'opacity': '1.0'
		});
		$(this).children('a').css({
			'display': 'none'
		});
	}
});
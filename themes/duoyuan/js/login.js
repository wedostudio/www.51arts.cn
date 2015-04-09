$(document).ready(function() { // set default for login
	$('#login_wrap input').focus(function() {
		$(this).siblings('span').css({
			'display': 'none'
		});
	}).blur(function() {
		if (this.value.length == 0)
			$(this).siblings('span').css({
				'display': 'inline'
			});
	});
});
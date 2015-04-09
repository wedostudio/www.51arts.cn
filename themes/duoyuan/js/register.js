// for register prompt
$(document).ready(function() {
	$("#register_content input").blur(function() {
		if (this.value.length == 0) {
			$(this).css({
				'background-color': '#ffe7e7'
			});
		} else {
			$(this).css({
				'background-color': '#fff'
			});
		}
	});
});
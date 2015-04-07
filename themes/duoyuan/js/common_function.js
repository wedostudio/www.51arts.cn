// for Bookmark
function favorites() {
	try {
		if (document.all) { // IE
			window.external.addFavorite(window.location, document.title);
		} else {
			window.sidebar.addPanel(document.title, window.location);
		}
	} catch (e) {
		var ctrl = (navigator.userAgent.toLowerCase()).indexOf('mac') != -1 ? 'Command/Cmd' : 'CTRL';
		alert('您可以使用快捷键 ' + ctrl + ' + D 添加到书签');
	}
}

// for div select
jQuery.divselect = function(divselectid, inputselectid) {
	var inputselect = $(inputselectid);
	$(divselectid + " cite").bind("click", function() {
		var ul = $(divselectid + " ul");
		if (ul.css("display") == "none") {
			ul.slideDown("fast");
		} else {
			ul.slideUp("fast");
		}
		return false;
	});
	$(divselectid + " ul li a").click(function() {
		var txt = $(this).text();
		$(divselectid + " cite").html(txt);
		var value = $(this).attr("selectid");
		inputselect.val(value);
		$(divselectid + " ul").hide();

	});
	$(document).click(function() {
		$(divselectid + " ul").hide();
	});
};



$(function(){
	navclick(dq)
	$('.footbg a').click(function(){
		var dq=$(this).index();
		navclick(dq)
		if(dq==1){
			$('div.foot ul').slideToggle(100);
		}else{
			$('div.foot ul').slideUp(100);
		}
	})
	
	$('.jiao').parent().click(function(){
		$(this).find('.jiao').toggleClass('dq');
	})
})


function navclick(obj){
	$('footer a:eq('+obj+'),.footjiao span:eq('+obj+')').addClass('on').siblings().removeClass('on');
}
function input_fouse(obj){
	$(obj).each(function() {
		$(this).focus(function() {
			$(this).parent().siblings('label.fl-left').hide();
		}).blur(function() {
			var val = $(this).val();
			if (val == "") {
				$(this).parent().siblings('label.fl-left').show();
			} 
		});
	});
}

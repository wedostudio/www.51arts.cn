/*
 *  SliderNav - A Simple Content Slider with a Navigation Bar
 *  Copyright 2010 Monjurul Dolon, http://mdolon.com/
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://devgrow.com/slidernav
 */
$.fn.sliderNav = function(options) {
	var defaults = { items: ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"], debug: false, height: null, arrows: true};
	var _hei=$('header').height()+$('.mapsearch_div').height()+$('.mappai_top').height()+$('footer').height();
	var opts = $.extend(defaults, options); var o = $.meta ? $.extend({}, opts, $$.data()) : opts; var slider = $(this); $(slider).addClass('slider');
	$('.slider-content li:first', slider).addClass('selected');
	$(slider).append('<div class="zipai_right center"><ul></ul></div>');
	for(var i in o.items) $('.zipai_right ul', slider).append("<li><a alt='#"+o.items[i]+"'>"+o.items[i]+"</a></li>");
	
	var zili=($(window).height()-_hei)/26
	zili=Math.floor(zili) 
	$('.zipai_right li').css({height:zili+'px',lineHeight:zili+'px'});
	
	var height = $('.zipai_right', slider).height();
	if(o.height) height = o.height;
	$('.slider-content, .zipai_right', slider).css('height',$(window).height()-_hei);
	if(o.debug) $(slider).append('<div id="debug">Scroll Offset: <span>0</span></div>');
	$('.zipai_right a', slider).mouseover(function(event){
		var target = $(this).attr('alt');
		var cOffset = $('.slider-content', slider).offset().top;
		var tOffset = $('.slider-content '+target, slider).offset().top;
		var height = $('.zipai_right', slider).height(); if(o.height) height = o.height;
		var pScroll = tOffset - cOffset+1;
		$('.slider-content li', slider).removeClass('selected');
		$(target).addClass('selected');
		$('.slider-content', slider).stop().animate({scrollTop: '+=' + pScroll + 'px'});
		if(o.debug) $('#debug span', slider).html(tOffset);
	});
	if(o.arrows){
		$('.zipai_right',slider).css('top','0px');
		//$(slider).prepend('<div class="slide-up end"><span class="arrow up"></span></div>');
		//$(slider).append('<div class="slide-down"><span class="arrow down"></span></div>');
		$('.slide-down',slider).click(function(){
			$('.slider-content',slider).animate({scrollTop : "+="+height+"px"}, 500);
		});
		$('.slide-up',slider).click(function(){
			$('.slider-content',slider).animate({scrollTop : "-="+height+"px"}, 500);
		});
	}
};
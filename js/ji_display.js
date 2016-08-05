/**
 * Created by liu on 16-8-5.
 * The main display framework in JI-LIFE
 */
(function (factory)
{
	if (typeof define === 'function' && define.amd)
	{
		// AMD. Register as anonymous module.
		define(['jquery'], factory);
	}
	else if (typeof exports === 'object')
	{
		// Node / CommonJS
		factory(require('jquery'));
	}
	else
	{
		// Browser globals.
		factory(jQuery);
	}
})(function ($)
{
	'use strict';
	
	function JIDisplay($element, option)
	{
		this.$container = $element;
		this.option = option;
		
		this.initFrame();
		
		this.init();
	}
	
	JIDisplay.prototype = {
		
		constructor: JIDisplay,
		
		init: function ()
		{
			this.addListener();
			
		},
		
		initFrame: function ()
		{
			var html = [
				'<div class="row">',
				'<div class="col-xs-3">',
				'<div class="card ji-side-bar">',
				'<div class="card-header">',
				'<h5></h5>',
				'</div>',
				'<div class="list-group ">',
				'</div>',
				'</div>',
				'</div>',
				'<div class="col-xs-9">',
				'<div class="card ji-side-card" data-text="all">',
				'<div class="card-header">',
				'<h5></h5>',
				'</div>',
				'</div>',
				'</div>',
				'</div>'
			].join('');
			this.$container.append(html);
		}
		
	};
	
	$.fn.JIDisplay = function (option)
	{
		return new JIDisplay(this, option);
	};
	
});


/*
 $(document).ready(function ()
 {
 var $setting_list = $(".ji-side-bar").first();
 var $setting_panel = $(".ji-side-card").first();
 $setting_list.find("a").click(function (e)
 {
 $setting_list.find("a").removeClass('active');
 var $target = $(e.target);
 $target.addClass('active');
 var text = $target.attr('data-text');
 var $card = $setting_panel.find(".card[data-text=" + text + "]");
 console.log($card);
 $setting_panel.find(".card").css("display", "none");
 $card.css("display", "block");
 });
 });*/

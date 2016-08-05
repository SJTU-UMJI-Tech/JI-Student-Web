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
		
		this.$bar = this.$container.find(".ji-side-bar");
		this.initBar();
		this.$barList = this.$bar.find(".list-group-item");
		
		this.$card = this.$container.find(".ji-side-card");
		this.$cardHeader = this.$card.find(".card-header h5");
		
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
				'<h5>', this.option.title, '</h5>',
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
		},
		
		initBar: function ()
		{
			var html;
			for (var key in this.option.type)
			{
				html = '<a href="javascript:void(0);" class="list-group-item list-group-item-action" data-text="' + key + '">' + this.option.type[key] + '</a>';
				this.$bar.append(html);
			}
		},
		
		addListener: function ()
		{
			this.$barList.on('click', $.proxy(this.onClickBarList, this));
		},
		
		onClickBarList: function (e)
		{
			this.switchCard($(e.target));
		},
		
		switchCard: function ($barItem)
		{
			this.$barList.removeClass('active');
			$barItem.addClass('active');
			var text = $barItem.attr('data-text');
			this.$cardHeader.html(this.option.type[text]);
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

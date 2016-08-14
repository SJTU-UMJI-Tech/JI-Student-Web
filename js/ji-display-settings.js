/**
 * Created by liu on 2016/8/8.
 */
;(function (factory)
{
	if (typeof define === 'function' && define.amd)
	{
		// AMD. Register as anonymous module.
		define(['jquery', 'marked', './ji-display'], factory);
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
})(function ($, marked)
{
	'use strict';
	
	function JIDisplaySettings()
	{
		
	}
	
	JIDisplaySettings.prototype = {
		
		constructor: JIDisplaySettings,
		
		scholarship: function ()
		{
			//window.console.log(marked);
			var generate = function (data)
			{
				//window.console.log(data);
				var detail_id = 'scholarships-detail-' + data.id;
				var html = [
					'<div class="card-block">',
					'<h4 class="card-title">', data.title, '</h4>',
					'<p class="card-text">', marked(data.abstract), '</p>',
					'<div class="card-text text-xs-center">',
					'<a class="btn btn-link" data-toggle="collapse" data-target="#' + detail_id +
					'" aria-expanded="false" aria-controls="' + detail_id + '">',
					'Details&nbsp;<i class="fa fa-angle-double-down" aria-hidden="true"></i>',
					'</a>',
					'</div>',
					'<div class="collapse" id="' + detail_id + '">',
					'<div class="card-text">', marked(data.content), '</div>',
					'</div>',
					'</div>'
				].join('');
				return html;
			};
			
			var onClickNew = function ($target)
			{
				window.location.href = '/scholarships/edit';
			};
			
			var model = {
				url: '/scholarships/ajax',
				sort: ['Newest', 'Oldest'],
				primary: 'id',
				limit: 20,
				generate: generate
			};
			
			var display = $("#ji-display").jiDisplay({
				title: 'Scholarships',
				item: {
					all: {
						name: 'All scholarships'
					},
					
					undergraduate: {
						name: 'Undergraduates'
					},
					
					graduate: {
						name: 'Graduates'
					},
					
					my: {
						name: 'My scholarships'
					},
					
					new: {
						name: 'Create scholarships',
						custom : onClickNew
					}
				},
				model: model
			});
			var name = 'all';
			var $barItem = display.$bar.find(".list-group-item[data-text='" + name + "']");
			display.switchCard($barItem);
		}
	};
	
	$.fn.jiDisplaySettings = function ()
	{
		return new JIDisplaySettings();
	};
	
});
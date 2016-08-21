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
		
		scholarships: function ()
		{
			var generate = function (data)
			{
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
			
			var model = {
				url: '/scholarships/ajax_search',
				sort: ['Newest', 'Oldest'],
				primary: 'id',
				limit: 2,
				generate: generate
			};
			
			var config = {
				category: 'event',
				id: 'id',
				title: 'title',
				abstract: 'abstract',
				detail: 'content'
			};
			
			var display = $("#ji-display").jiDisplay({
				title: 'Scholarships',
				height: '800',
				item: {
					all: {
						name: 'All scholarships',
						type: 'list',
						config: config
					},
					
					undergraduate: {
						name: 'Undergraduates',
						type: 'list',
						config: config
					},
					
					graduate: {
						name: 'Graduates',
						type: 'list',
						config: config
					},
					
					my: {
						name: 'My scholarships',
						type: 'list',
						config: config
					},
					
					new: {
						name: 'Create scholarships',
						type: 'href',
						href: '/scholarships/edit'
					}
				},
				model: model
			});
			var name = 'all';
			var $barItem = display.$bar.find(".list-group-item[data-text='" + name + "']");
			display.switchCard($barItem);
		},
		advising: function ()
		{
			//window.console.log(marked);
			var generate = function (data)
			{
				1;
			};
			
			var onClickNew = function ($target)
			{
				window.location.href = '/advising/edit';
			};
			
			var model = {
				url: '/advising/ajax',
				sort: ['Newest', 'Oldest'],
				primary: 'id',
				limit: 20,
				generate: generate
			};
			
			var display = $("#ji-display").jiDisplay({
				title: 'Advising',
				item: {
					info: {
						name: 'About us',
						type: 'intro',
						url: '/advising/ajax_intro',
						//text: '# Introduction\n# Introduction\n# Introduction\n# Introduction\n'
					},
					
					members: {
						name: 'Members',
						type: 'list',
						url: '/advising/ajax_member',
						//sort: ['Newest', 'Oldest'],
						primary: 'id',
						limit: 0,
						generate: generate
					},
					
					calender: {
						name: 'Calender'
					},
					
					events: {
						name: 'Events'
					},
					
					booking: {
						name: 'Booking'
					},
					
					new: {
						name: 'Create events',
						type: 'href',
						href: '/advising/edit'
					}
				},
				//model: model
			});
			var name = 'info';
			var $barItem = display.$bar.find(".list-group-item[data-text='" + name + "']");
			display.switchCard($barItem);
		},
		
		career: function ()
		{
			//window.console.log(marked);
			var generate = function (data)
			{
				1;
			};
			
			var onClickNew = function ($target)
			{
				window.location.href = '/career/edit';
			};
			
			var model = {
				url: '/career/ajax',
				sort: ['Newest', 'Oldest'],
				primary: 'id',
				limit: 20,
				generate: generate
			};
			
			var display = $("#ji-display").jiDisplay({
				title: 'Career',
				item: {
					info: {
						name: 'About Us'
					},
					
					announcement: {
						name: 'Announcement'
					},
					
					jobs: {
						name: 'Jobs & Internships'
					},
					
					activities: {
						name: 'Workshops & Activities'
					},
					
					mentors: {
						name: 'Career Mentors'
					},
					
					resources: {
						name: 'Resources'
					},
					
					new: {
						name: 'Create Announcement',
						custom: onClickNew
					}
				},
				model: model
			});
			var name = 'announcement';
			var $barItem = display.$bar.find(".list-group-item[data-text='" + name + "']");
			display.switchCard($barItem);
		}
	};
	
	$.fn.jiDisplaySettings = function ()
	{
		return new JIDisplaySettings();
	};
	
});
/**
 * Created by liu on 16-8-5.
 * The main display framework in JI-LIFE
 */
;(function (factory)
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
		this.item = {};
		this.$container = $element;
		this.option = option;
		
		this.initFrame();
		
		this.$bar = this.$container.find(".ji-side-bar");
		this.initBar();
		this.$barList = this.$bar.find(".list-group-item");
		
		this.$card = this.$container.find(".ji-side-card");
		this.$cardName = this.$card.find(".card-name h5");
		this.$cardSearch = this.$card.find(".card-search");
		this.$cardSearchInput = null;
		this.$cardSearchButton = null;
		this.$cardBody = this.$card.find(".card-body");
		
		this.searchLock = new Date().getTime();
		this.searchText = '';
		this.searchResult = [];
		this.searchResultId = {};
		this.searchNow = 0;
		
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
				'<div class="col-md-3">',
				'<div class="card ji-side-bar navbar-fixed">',
				'<div class="card-header"><h5>', this.option.title, '</h5></div>',
				'<div class="list-group ">',
				'</div>',
				'</div>',
				'</div>',
				'<div class="col-md-9">',
				'<div class="card ji-side-card" data-text="all">',
				'<div class="card-header">',
				'<div class="row">',
				'<div class="card-name col-xs-12 col-md-4"><h5></h5></div>',
				'<div class="card-search col-sm-12 col-md-8 col-lg-6 col-xl-5 pull-xs-right"></div>',
				'</div>',
				'</div>',
				'<div class="card-body"></div>',
				'</div>',
				'</div>',
				'</div>'
			].join('');
			this.$container.append(html);
		},
		
		initBar: function ()
		{
			var html;
			for (var key in this.option.item)
			{
				html = '<a href="javascript:void(0);" class="list-group-item list-group-item-action" data-text="' + key + '">' + this.option.item[key].name + '</a>';
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
			var key = $barItem.attr('data-text');
			this.item = this.option.item[key];
			this.$cardName.html(this.item.name);
			this.refreshSearch();
			this.$cardBody.html('');
			this.ajaxSearch(true);
		},
		
		refreshSearch: function ()
		{
			var html =
				'<div class="input-group">' +
				'<input type="text" class="form-control form-control-sm" placeholder="Search">';
			if (this.item.sort)
			{
				html += [
					'<div class="input-group-btn">',
					'<button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">',
					this.item.sort[0], '</button>',
					'<div class="dropdown-menu dropdown-menu-right">'
				].join('');
				for (var index in this.item.sort)
				{
					html += '<a class="dropdown-item" href="javascript:void(0);" data-text="' + this.item.sort[index] + '">' + this.item.sort[index] + '</a>';
				}
				html += '</div></div>';
			}
			html += '</div>';
			this.$cardSearch.html(html);
			this.searchText = '';
			this.$cardSearch.find("a").on('click', $.proxy(this.onClickSort, this));
			this.$cardSearchButton = this.$cardSearch.find("button");
			this.$cardSearchInput = this.$cardSearch.find("input");
			this.$cardSearchInput.on('keyup', $.proxy(this.onSearchChange, this));
		},
		
		onClickSort: function (e)
		{
			var text = $(e.target).attr('data-text');
			if (this.$cardSearchButton.attr('data-text') != text)
			{
				this.$cardSearchButton.html(text).attr('data-text', text);
				this.ajaxSearch(true);
			}
		},
		
		onSearchChange: function ()
		{
			var str = this.$cardSearchInput.val().replace(/(^\s*)|(\s*$)/g, "");
			if (str != this.searchText)
			{
				this.searchText = str;
				this.ajaxSearch(true);
			}
		},
		
		ajaxSearch: function (refreshFlag)
		{
			if (refreshFlag)
			{
				this.searchResult = [];
				this.searchResultId = {};
				this.searchNow = 0;
				this.$cardBody.html('');
			}
			var _this = this;
			var searchTime = this.searchLock = new Date().getTime();
			$.ajax({
				type: 'GET',
				url: this.item.url,
				data: {
					cmd: 'search',
					key: this.item.key,
					keywords: this.searchText,
					order: this.$cardSearchButton.attr('data-text'),
					limit: this.item.limit,
					offset: this.searchResult.length
				},
				dataType: 'text',
				success: function (data)
				{
					if (searchTime != _this.searchLock)
					{
						return;
					}
					_this.ajaxLoad(data);
				},
				error: function ()
				{
					_this.$cardBody.html('There is some connection error!');
					window.console.log('There is some connection error!');
				}
			});
		},
		
		ajaxLoad: function (data)
		{
			data = JSON.parse(data);
			for (var index in data)
			{
				var id = data[index][this.item.primary];
				if (!this.searchResultId.hasOwnProperty(id))
				{
					this.searchResultId[id] = 1;
					this.searchResult.push(data[index]);
					window.console.log(this.searchResult);
				}
			}
			if (this.searchNow >= this.searchResult.length)
			{
				if (this.searchNow == 0)
				{
					window.console.log('No data found!');
				}
				else
				{
					window.console.log('No more data!');
				}
				return;
			}
			var last = Math.min(this.searchNow + 10, this.searchResult.length);
			for (index = this.searchNow; index < last; index++)
			{
				var html = this.item.generate(this.searchResult[index]);
				this.$cardBody.append(html);
				this.$cardBody.append('<div class="dropdown-divider"></div>');
			}
			this.$cardBody.append('<div class="card-block text-xs-center"><div class="card-text">Loading more data</div></div>');
			this.searchNow = last;
			
		}
	};
	
	$.fn.JIDisplay = function (option)
	{
		return new JIDisplay(this, option);
	};
	
});
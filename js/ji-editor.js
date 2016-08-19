/**
 * Created by liu on 2016/8/11.
 */
;(function (factory)
{
	if (typeof define === 'function' && define.amd)
	{
		// AMD. Register as anonymous module.
		var deps = [
			"jquery",
			"editormd",
			"jquery.cookie",
			"jquery.md5",
			'bootstrapDatetimepicker',
			'jquery.fileupload-ui',
			"../vendors/editor.md-1.5.0/languages/en",
			"../vendors/editor.md-1.5.0/plugins/link-dialog/link-dialog",
			"../vendors/editor.md-1.5.0/plugins/reference-link-dialog/reference-link-dialog",
			"../vendors/editor.md-1.5.0/plugins/image-dialog/image-dialog",
			"../vendors/editor.md-1.5.0/plugins/code-block-dialog/code-block-dialog",
			"../vendors/editor.md-1.5.0/plugins/table-dialog/table-dialog",
			"../vendors/editor.md-1.5.0/plugins/emoji-dialog/emoji-dialog",
			"../vendors/editor.md-1.5.0/plugins/goto-line-dialog/goto-line-dialog",
			"../vendors/editor.md-1.5.0/plugins/help-dialog/help-dialog",
			"../vendors/editor.md-1.5.0/plugins/html-entities-dialog/html-entities-dialog",
			"../vendors/editor.md-1.5.0/plugins/preformatted-text-dialog/preformatted-text-dialog"
		];
		define(deps, factory);
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
})(function ($, editormd)
{
	editormd.loadCSS("../vendors/editor.md-1.5.0/lib/codemirror/addon/fold/foldgutter");
	
	function JIEditor($element, option)
	{
		this.$container = $element;
		this.option = option;
		this.optionID = {};
		
		this.$autosave = this.$container.find(".autosave");
		this.autosaveFlag = false;
		this.$submitBtn = this.$container.find(".btn-submit");
		
		this.$submitBtn.on('click', $.proxy(this.onSubmit, this));
		
		for (var index in this.option.item)
		{
			this.option.item[index].$element = this.$container.find("[name=" + this.option.item[index].name + "]");
			this.optionID[this.option.item[index].name] = index;
		}
		
		this.initMarkdown();
		this.initDropdown();
		this.initDatetimepicker();
		this.initFileupload();
	}
	
	JIEditor.prototype = {
		
		constructor: JIEditor,
		
		initMarkdown: function ()
		{
			var _this = this;
			this.$container.find(".ji-markdown").each(function ()
			{
				_this.option.item[_this.optionID[$(this).attr('name')]].editor
					= editormd($(this).attr('id'), {
					width: "100%",
					height: 800,
					syncScrolling: "single",
					path: "../vendors/editor.md-1.5.0/lib/",
					saveHTMLToTextarea: true,
					flowchart: true
				});
			});
		},
		
		initDropdown: function ()
		{
			this.$container.find(".ji-dropdown").each(function ()
			{
				var $button = $(this).find("button"),
					$buttons = $(this).find("a"),
					text = $button.data('text'),
					$def = text ? $(this).find("a[data-text=" + text + "]") : $buttons.first();
				$button.data('text', $def.data('text'))
				       .html($def.html());
				$buttons.click(function (e)
				{
					$button.data('text', $(e.target).data('text'))
					       .html($(e.target).html());
				});
			});
		},
		
		initDatetimepicker: function ()
		{
			this.$container.find(".ji-date").each(function ()
			{
				var format, minView;
				if ($(this).attr('type') == 'date')
				{
					format = "yyyy-mm-dd";
					minView = 'month';
				}
				else
				{
					format = "yyyy-mm-dd hh:ii";
					minView = 'hour';
				}
				$(this).datetimepicker({
					format: format,
					minView: minView,
					autoclose: true,
					todayBtn: true,
					pickerPosition: "bottom-right",
					todayHighlight: true,
					keyboardNavigation: true,
					fontAwesome: true
				});
			});
		},
		
		initFileupload: function ()
		{
			this.$container.find(".ji-file").fileupload({
				dataType: 'json',
				url: '/upload/'
			});
		},
		
		onSubmit: function ()
		{
			var data = this.serialize();
			var _this = this;
			$.ajax({
				type: 'POST',
				url: this.option.url,
				data: {
					id: this.option.id,
					data: JSON.stringify(data)
				},
				dataType: 'text',
				success: function (data)
				{
					if (data[0] == '/')
					{
						var name = _this.getCookieName();
						$.cookie(name, '', {expires: -1});
						this.autosaveFlag = false;
						window.location.href = data;
					}
					else
					{
						alert(data);
					}
				},
				error: function ()
				{
					_this.$cardBody.html('There is some connection error!');
					window.console.log('There is some connection error!');
				}
			});
		},
		
		unserialize: function (data)
		{
			//console.log(data);
			if (!data)
			{
				return;
			}
			for (var name in data)
			{
				var item = this.option.item[this.optionID[name]];
				var value = data[name];
				switch (item.type)
				{
				case 'text':
				case 'textarea':
				case 'date':
				case 'time':
					item.$element.val(value);
					break;
				case 'markdown':
					item.editor.setValue(value);
					break;
				case 'dropdown':
					item.$element.data('text', value);
					item.$element.html(item.$element.parent().find("a[data-text=" + value + "]").html());
					break;
				}
			}
			var date = new Date((new Date()).getTime() + 3600000 * 8);
			this.$autosave.html('Loaded at ' + date.toUTCString());
		},
		
		serialize: function ()
		{
			var data = {};
			for (var index in this.option.item)
			{
				var item = this.option.item[index];
				var value;
				switch (item.type)
				{
				case 'text':
				case 'textarea':
				case 'date':
				case 'time':
					value = item.$element.val();
					break;
				case 'markdown':
					value = item.editor.getMarkdown();
					break;
				case 'dropdown':
					value = item.$element.data('text');
					break;
				case 'file':
					value = this.serializeFile(item.$element);
				}
				data[item.name] = value;
			}
			//console.log(data);
			return data;
		},
		
		serializeFile: function ($element)
		{
			var data = [];
			$element.find(".template-download").each(function ()
			{
				var size = $(this).find(".size").html();
				if (size)
				{
					data.push({
						//title: $(this).find(".name a").attr('title'),
						url: $(this).find(".delete").data('url'),
						size: size
					});
				}
			});
			return data;
		},
		
		saveCookie: function ()
		{
			var name = this.getCookieName();
			$.cookie(name, JSON.stringify(this.serialize()), {expires: 90});
		},
		
		loadCookie: function ()
		{
			var name = this.getCookieName();
			var data = $.cookie(name);
			return data ? JSON.parse(data) : {};
		},
		
		autosave: function (time)
		{
			var _this = this;
			this.autosaveFlag = true;
			var intervalId = setInterval(function ()
			{
				if (!_this.autosaveFlag)
				{
					clearInterval(intervalId);
				}
				_this.saveCookie();
				var date = new Date((new Date()).getTime() + 3600000 * 8);
				_this.$autosave.html('Autosaved at ' + date.toUTCString());
			}, time);
		},
		
		getCookieName: function ()
		{
			return $.md5(this.option.type + '-' + this.option.user + '-' + this.option.id);
		}
		
	};
	
	$.fn.jiEditor = function (option)
	{
		return new JIEditor(this, option);
	};
	
});
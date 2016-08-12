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
			"jqueryCookie",
			"jqueryMD5",
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
		
		var _this = this;
		for (var index in this.option.item)
		{
			this.option.item[index].$element = this.$container.find("[name=" + this.option.item[index].name + "]");
			this.optionID[this.option.item[index].name] = index;
		}
		this.$container.find(".editormd").each(function ()
		{
			_this.option.item[_this.optionID[$(this).attr('name')]].editor
				= _this.initMarkdown($(this).attr('id'));
		});
	}
	
	JIEditor.prototype = {
		
		constructor: JIEditor,
		
		initMarkdown: function (id)
		{
			var markdown = editormd(id, {
				width: "100%",
				height: 640,
				syncScrolling: "single",
				path: "../vendors/editor.md-1.5.0/lib/",
				saveHTMLToTextarea: true,
				flowchart: true
			});
			return markdown;
		},
		
		unserialize: function (data)
		{
			for (var index in data)
			{
				var item = this.option.item[this.optionID[data[index].name]];
				var value = data[index].value;
				if (item.type == 'text' || item.type == 'textarea')
				{
					item.$element.val(value);
				}
				else if (item.type == 'editor')
				{
					item.editor.setValue(value);
				}
			}
			var date = new Date((new Date()).getTime() + 3600000 * 8);
			this.$autosave.html('Loaded at ' + date.toUTCString());
		},
		
		serialize: function ()
		{
			var data = [];
			for (var index in this.option.item)
			{
				var item = this.option.item[index];
				var value;
				if (item.type == 'text' || item.type == 'textarea')
				{
					value = item.$element.val();
				}
				else if (item.type == 'editor')
				{
					value = item.editor.getMarkdown();
				}
				data.push({name: item.name, value: value});
			}
			return data;
		},
		
		saveCookie: function (user, id)
		{
			var name = $.md5(this.option.type + '-' + user + '-' + id);
			$.cookie(name, JSON.stringify(this.serialize()), {expires: 90});
		},
		
		loadCookie: function (user, id)
		{
			var name = $.md5(this.option.type + '-' + user + '-' + id);
			var data = $.cookie(name);
			return data ? JSON.parse(data) : {};
		},
		
		autosave: function (user, id, time)
		{
			var _this = this;
			setInterval(function ()
			{
				_this.saveCookie(user, id);
				var date = new Date((new Date()).getTime() + 3600000 * 8);
				_this.$autosave.html('Autosaved at ' + date.toUTCString());
			}, time);
		}
		
	};
	
	$.fn.jiEditor = function (option)
	{
		return new JIEditor(this, option);
	};
	
});
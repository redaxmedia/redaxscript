(function ($)
{
	/* editor */

	$.fn.editor = function (options)
	{
		/* extend options */

		if (r.module.editor.options != options)
		{
			options = $.extend({}, r.module.editor.options, options || {});
		}

		window.editor = this;
		this.textarea = $(this);

		/* prematurely terminate editor */

		if (this.textarea.length < 1)
		{
			return false;
		}

		this.textarea.hide();
		this.editor = $('<div class="js_editor editor"></div>').insertBefore(this.textarea);
		this.toolbar = $('<div class="js_toolbar editor_toolbar" unselectable="on"></div>').appendTo(this.editor);

		/* create toolbar */

		this.createToolbar = function ()
		{
			var name, data, control, i;

			for (i = 0; i < options.control.length; i++)
			{
				name = options.control[i];
				data = r.module.editor.controls[name];
				
				/* append divider */
				
				if (name == 'divider')
				{
					$('<div class="js_editor_divider editor_divider"></div>').appendTo(editor.toolbar);
				}

				else if (name == 'newline')
				{
					$('<div class="js_editor_newline editor_newline"></div>').appendTo(editor.toolbar);
				}

				/* append toggler */

				else if (name == 'toggle')
				{
					editor.toggler = control = $('<div class="js_editor_control editor_control editor_control_source_code" title="' + data.title + '"></div>').appendTo(editor.toolbar);
				}

				/* append serveral controls */
	
				else if (data)
				{
					control = $('<div class="js_editor_control editor_control editor_control_' + name + '" title="' + data.title + '"></div>').appendTo(editor.toolbar);
				}

				/* setup control events */

				if (data)
				{
					control.data('data', data).mousedown(function ()
					{
						data = $(this).data('data');

						/* call methode */

						editor[data.methode](data.command, data.message, data.value);
						editor.post();
					});
				}
			}
		}();

		/* general action call */

		this.action = function (command)
		{
			if (this.checkSelection())
			{
				try
				{
					document.execCommand(command, 0, 0);

					/* fix mozilla styles from preview */

					if (r.constant.MY_BROWSER == 'firefox')
					{
						this.preview.removeAttr('style');
					}
				}

				/* alert dialog if no support */

				catch (exception)
				{
					$.fn.dialog(
					{
						type: 'alert',
						message: l.editor_browser_support_no + l.point
					});
				}
			}
		};

		/* general insert */

		this.insert = function (command, message, value)
		{
			if (command == 'createLink' && this.checkSelection() == '')
			{
				return false;
			}

			/* prompt dialog */

			$.fn.dialog(
			{
				type: 'prompt',
				message: message + l.colon,
				value: value,
				callback: function (input)
				{
					if (command == 'insertFunction')
					{
						editor.insertHTML('&lt;function&gt;' + input + '&lt;/function&gt;')();
					}
					else
					{
						editor.preview.focus();
						document.execCommand(command, 0, input);
					}
					editor.post();
				}
			});
		};

		/* insert html */

		this.insertHTML = function (text)
		{
			this.preview.focus();
			if (r.constant.MY_BROWSER == 'msie')
			{
				document.selection.createRange().pasteHTML(text);
			}
			else
			{
				document.execCommand('insertHTML', 0, text);
			}
		};

		/* insert code quote */

		this.insertCode = function ()
		{
			if (this.checkSelection())
			{
				this.insertHTML('&lt;code&gt;' + this.select() + '&lt;/code&gt;');
			}
		};

		/* insert document break */

		this.insertBreak = function ()
		{
			this.insertHTML('&lt;break&gt;');
		};

		/* alternate format */

		this.format = function (tag)
		{
			if (tag && this.checkSelection())
			{
				this.insertHTML('<' + tag + '>' + this.select() + '</' + tag + '>');
			}
		};

		/* get selection */

		this.select = function ()
		{
			if (r.constant.MY_BROWSER == 'msie')
			{
				var output = document.selection.createRange().text;
			}
			else
			{
				var output = window.getSelection().toString();
			}
			return output;
		};

		/* check for selected text */

		this.checkSelection = function ()
		{
			if (this.select())
			{
				return true;
			}
			else
			{
				/* alert dialog if no selection */

				$.fn.dialog(
				{
					type: 'alert',
					message: l.editor_select_text_first + l.point
				});
				return false;
			}
		};

		/* toggle between source code and wysiwyg */

		this.toggle = function ()
		{
			if (this.mode)
			{
				this.mode = 0;
				this.preview.html(this.convertToEntity()).focus();
				editor.toggler.attr('title', l.editor_source_code);
			}
			else
			{
				this.mode = 1;
				this.textarea.val(this.convertToHTML()).focus();
				editor.toggler.attr('title', l.editor_wysiwyg);
			}
			editor.toggler.toggleClass('editor_control_source_code editor_control_wysiwyg').nextAll('div.editor_control, div.editor_divider').toggle();
			this.textarea.add(this.preview).toggle();
		};

		/* convert to html */

		this.convertToHTML = function ()
		{
			var output = this.preview.html();

			/* pseudo tags */

			output = output.replace(/-&gt;/gi, '->');
			output = output.replace(/&lt;(break|code|function)&gt;/gi, '<$1>');
			output = output.replace(/&lt;\/(code|function)&gt;/gi, '</$1>');
			output = output.replace(/[\r\n]/gi, '');

			/* xhtml cleanup */

			if (options.xhtml)
			{
				output = output.replace(/ class="(apple-style-span|msonormal)"/gi, '');
				output = output.replace(/ class=""/gi, '');
				output = output.replace(/ style="(.*?)"/gi, '');
				output = output.replace(/<(\w+)>(\s)*<\/\1>/gi, '');
				output = output.replace(/<b>(.*?)<\/b>/gi, '<strong>$1</strong>');
				output = output.replace(/<i>(.*?)<\/i>/gi, '<em>$1</em>');
				output = output.replace(/<(s|strike)>(.*?)<\/(s|strike)>/gi, '<del>$2</del>');
				output = output.replace(/<br>/gi, '<br />');
				output = output.replace(/(<img [^>]+[^\/])>/gi, '$1 />');
			}

			/* add newlines */

			if (options.newline)
			{
				output = output.replace(/<br \/>/gi, '<br \/>\n');
				output = output.replace(/<\/h([1-6])>/gi, '<\/h$1>\n');
				output = output.replace(/<\/(div|li|ol|p|span|ul)>/gi, '<\/$1>\n');
				output = output.replace(/<(ol|ul)>/gi, '<$1>\n');
			}
			return output;
		};

		/* convert to entity */

		this.convertToEntity = function ()
		{
			var output = this.textarea.val();

			output = output.replace(/->/gi, '-&gt;');
			output = output.replace(/<(break|code|function)>/gi, '&lt;$1&gt;');
			output = output.replace(/<\/(code|function)>/gi, '&lt;/$1&gt;');
			return output;
		};

		/* post html to textarea */

		this.post = function ()
		{
			this.textarea.val(this.convertToHTML()).change();
		};

		/* append preview */

		this.preview = $('<div class="js_required js_editor_preview editor_preview" contenteditable="true">' + this.convertToEntity() + '</div>').appendTo(this.editor);

		/* insert break on enter */

		this.preview.on('keydown', function (event)
		{
			if (event.which == 13)
			{
				var output = '<br />';
				if (r.constant.MY_BROWSER == 'firefox')
				{
					output += '<div></div>';
				}
				if (r.constant.MY_BROWSER == 'msie')
				{
					houtputtml += '<span></span>';
				}
				if (r.constant.MY_ENGINE == 'webkit')
				{
					output += '<br />';
				}

				editor.insertHTML(output);
				event.preventDefault();
			}
		});

		/* post on keyup */

		this.preview.on('keyup', function ()
		{
			editor.post();
		});

		/* force xhtml */

		if (options.xhtml)
		{
			try
			{
				document.execCommand('styleWithCSS', 0, false);
			}
			catch (exception)
			{
				try
				{
					document.execCommand('useCSS', 0, true);
				}
				catch (exception)
				{
					return false;
				}
			}
		}
	};
})(jQuery);



/* detect needed mode */

(function ()
{
	if (r.constant.LAST_TABLE == 'articles' || (r.constant.ADMIN_PARAMETER == 'new' || r.constant.ADMIN_PARAMETER == 'edit') && (r.constant.TABLE_PARAMETER == 'articles' || r.constant.TABLE_PARAMETER == 'extras' || r.constant.TABLE_PARAMETER == 'comments'))
	{
		if (r.constant.FIRST_PARAMETER != 'admin')
		{
			r.module.editor.options.control = ['bold', 'italic', 'underline', 'strike', 'divider', 'unformat'];
			r.module.editor.options.newline = false;
		}

		/* startup */

		$(r.module.editor.selector).editor(r.module.editor.options);
	}
})();
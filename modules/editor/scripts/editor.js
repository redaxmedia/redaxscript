/**
 * @tableofcontents
 *
 * 1. editor
 *    1.1 create toolbar
 *    1.2 create preview
 *    1.3 action
 *    1.4 insert
 *    1.5 insert html
 *    1.6 insert code
 *    1.7 insert break
 *    1.8 format
 *    1.9 toggle
 *    1.10 get selection
 *    1.11 check selection
 *    1.12 convert to html
 *    1.13 convert to entity
 *    1.14 post
 *    1.15 validate
 *    1.16 init
 * 2. startup
 *
 * @since 2.0.2
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. editor */

	$.fn.editor = function (options)
	{
		/* extend options */

		if (r.modules.editor.options !== options)
		{
			options = $.extend({}, r.modules.editor.options, options || {});
		}

		/* detect needed mode */

		if (r.constants.LOGGED_IN === r.constants.TOKEN && r.constants.FIRST_PARAMETER === 'admin')
		{
			options.toolbar = options.toolbar.backend;
			options.xhtml = options.xhtml.backend;
			options.breakOnEnter = options.breakOnEnter.backend;
			options.newline = options.newline.backend;
		}
		else
		{
			options.toolbar = options.toolbar.frontend;
			options.xhtml = options.xhtml.frontend;
			options.breakOnEnter = options.breakOnEnter.frontend;
			options.newline = options.newline.frontend;
		}

		/* return this */

		return this.each(function ()
		{
			var editor =
				{
					textarea: $(this)
				};

			/* @section 1.1 create toolbar */

			editor.createToolbar = function ()
			{
				/* append toolbar */

				editor.toolbar = $('<div unselectable="on">').addClass(options.classString.editorToolbar).appendTo(editor.container);

				/* append controls */

				for (var i in options.toolbar)
				{
					var name = options.toolbar[i],
						data = r.modules.editor.controls[name],
						control = '';

					/* append toggle */

					if (name === 'toggle')
					{
						editor.controlToggle = control = $('<a title="' + data.title + '"></a>').addClass(options.classString.editorControl + ' ' + options.classString.editorSourceCode).appendTo(editor.toolbar);
					}

					/* append several controls */

					else if (typeof data === 'object')
					{
						control = $('<a title="' + data.title + '"></a>').addClass(options.classString.editorControl + ' ' + name).appendTo(editor.toolbar);
					}

					/* handle control events */

					if (typeof data === 'object')
					{
						/* closure for loop */

						(function (data)
						{
							/* listen for mousedown to prevent unselect */

							control.on('mousedown', function ()
							{
								/* call related method */

								editor[data.method](data.command, data.message, data.value);
								editor.post();

								/* haptic feedback */

								if (r.support.vibrate && typeof options.vibrate === 'number')
								{
									window.navigator.vibrate(options.vibrate);
								}
							});
						}(data));
					}
				}
			};

			/* @section 1.2 create preview */

			editor.createPreview = function ()
			{
				/* append preview */

				editor.preview = $('<div contenteditable="true">' + editor.convertToEntity() + '</div>').addClass(options.classString.editorPreview).appendTo(editor.container);

				/* insert break on enter */

				if (options.breakOnEnter)
				{
					editor.preview.on('keydown', function (event)
					{
						if (event.which === 13)
						{
							if (r.constants.MY_ENGINE === 'gecko')
							{
								document.execCommand('insertBrOnReturn', false, false);
							}
							else if (r.constants.MY_ENGINE === 'trident')
							{
								editor.insertHTML('<br />');
								event.preventDefault();
							}
							else if (r.constants.MY_ENGINE === 'webkit')
							{
								editor.insertHTML('<br /><br />');
								event.preventDefault();
							}
						}
					});
				}

				/* post and validate on keyup */

				editor.preview.on('keyup', function ()
				{
					editor.post();
					editor.validate();
				});
			};

			/* @section 1.3 action */

			editor.action = function (command)
			{
				if (editor.checkSelection())
				{
					try
					{
						document.execCommand(command, 0, 0);
					}

					/* alert dialog if no support */

					catch (exception)
					{
						$.fn.dialog(
						{
							message: l.editor.browser_support_no + l.point
						});
					}
				}
			};

			/* @section 1.4 insert */

			editor.insert = function (command, message, value)
			{
				/* prompt dialog */

				$.fn.dialog(
				{
					type: 'prompt',
					message: message + l.colon,
					value: value,
					callback: function (input)
					{
						/* create link without selection */

						if (command === 'createLink')
						{
							editor.insertHTML('<a href="' + input + '">' + input + '</a>');
						}

						/* insert function */

						else if (command === 'insertFunction')
						{
							editor.insertHTML('&lt;function&gt;' + input + '&lt;/function&gt;');
						}

						/* else default behavior */

						else
						{
							editor.preview.focus();
							document.execCommand(command, 0, input);
						}
						editor.post();
					}
				});
			};

			/* @section 1.5 insert html */

			editor.insertHTML = function (text)
			{
				editor.preview.focus();
				if (typeof document.selection === 'object' && typeof document.selection.createRange === 'function' && typeof document.selection.createRange().pasteHTML === 'function')
				{
					document.selection.createRange().pasteHTML(text);
				}
				else
				{
					document.execCommand('insertHTML', 0, text);
				}
			};

			/* @section 1.6 insert code */

			editor.insertCode = function ()
			{
				if (editor.checkSelection())
				{
					editor.insertHTML('&lt;code&gt;' + editor.select() + '&lt;/code&gt;');
				}
			};

			/* @section 1.7 insert break */

			editor.insertBreak = function ()
			{
				editor.insertHTML('&lt;break&gt;');
			};

			/* @section 1.8 format */

			editor.format = function (tag)
			{
				if (tag && editor.checkSelection())
				{
					editor.insertHTML('<' + tag + '>' + editor.select() + '</' + tag + '>');
				}
			};

			/* @section 1.9 toggle */

			editor.toggle = function ()
			{
				if (editor.mode)
				{
					editor.mode = 0;
					editor.preview.html(editor.convertToEntity()).focus();
					editor.controlToggle.attr('title', l.editor.source_code);
				}
				else
				{
					editor.mode = 1;
					editor.textarea.val(editor.convertToHTML()).focus();
					editor.controlToggle.attr('title', l.editor.wysiwyg);
				}
				editor.controlToggle.toggleClass(options.classString.editorSourceCode + ' ' + options.classString.editorWysiwyg).nextAll(options.element.editorControl).toggle();
				editor.textarea.add(editor.preview).toggle();
				editor.validate();
			};

			/* @section 1.10 get selection */

			editor.select = function ()
			{
				var output = '';

				if (typeof window.getSelection === 'function')
				{
					output = window.getSelection().toString();
				}
				else if (typeof document.selection === 'object' && typeof document.selection.createRange === 'function')
				{
					output = document.selection.createRange().text;
				}
				return output;
			};

			/* @section 1.11 check selection */

			editor.checkSelection = function ()
			{
				if (editor.select())
				{
					return true;
				}
				else
				{
					/* alert dialog if no selection */

					$.fn.dialog(
					{
						message: l.editor.select_text_first + l.point
					});
				}
			};

			/* @section 1.12 convert to html */

			editor.convertToHTML = function ()
			{
				var output = editor.preview.html(),
					eol = options.eol;

				/* pseudo tags */

				output = output.replace(/&lt;(break|code|function)&gt;/gi, '<$1>');
				output = output.replace(/&lt;\/(code|function)&gt;/gi, '</$1>');

				/* xhtml cleanup */

				if (options.xhtml)
				{
					output = output.replace(/ class=""/gi, '');
					output = output.replace(/ style="(.*?)"/gi, '');
					output = output.replace(/<b>(.*?)<\/b>/gi, '<strong>$1</strong>');
					output = output.replace(/<i>(.*?)<\/i>/gi, '<em>$1</em>');
					output = output.replace(/<(s|strike)>(.*?)<\/(s|strike)>/gi, '<del>$2</del>');
					output = output.replace(/<br>/gi, '<br />');
					output = output.replace(/(<img [^>]+[^\/])>/gi, '$1 />');
				}

				/* add newlines */

				if (options.newline)
				{
					output = output.replace(/<br \/>/gi, '<br \/>' + eol);
					output = output.replace(/<\/h([1-6])>/gi, '<\/h$1>' + eol);
					output = output.replace(/<\/(div|li|ol|p|span|ul)>/gi, '<\/$1>' + eol);
					output = output.replace(/<(ol|ul)>/gi, '<$1>' + eol);
					output = output.replace(window.RegExp(eol + eol, 'g'), eol);
				}
				return output;
			};

			/* @section 1.13 convert to entity */

			editor.convertToEntity = function ()
			{
				var output = editor.textarea.val();

				output = output.replace(/<(break|code|function)>/gi, '&lt;$1&gt;');
				output = output.replace(/<\/(code|function)>/gi, '&lt;/$1&gt;');
				return output;
			};

			/* @section 1.14 post */

			editor.post = function ()
			{
				var html = editor.convertToHTML();

				if (html)
				{
					editor.textarea.val(html).trigger('change');
				}
			};

			/* @section 1.15 validate */

			editor.validate = function ()
			{
				editor.textarea.add(editor.preview).attr('data-related', 'editor').trigger('related');
			};

			/* @section 1.16 init */

			editor.init = function ()
			{
				/* create editor elements */

				editor.textarea.hide();
				editor.container = $('<div>').addClass(options.classString.editor).insertBefore(editor.textarea);

				/* create toolbar */

				editor.createToolbar();
				editor.createPreview();
			};

			/* init as needed */

			if (editor.textarea.length)
			{
				editor.init();
			}
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.editor.startup && (r.constants.LAST_TABLE === 'articles' || (r.constants.ADMIN_PARAMETER === 'new' || r.constants.ADMIN_PARAMETER === 'edit') && (r.constants.TABLE_PARAMETER === 'articles' || r.constants.TABLE_PARAMETER === 'extras' || r.constants.TABLE_PARAMETER === 'comments')))
		{
			$(r.modules.editor.selector).editor(r.modules.editor.options);
		}
	});
})(window.jQuery || window.Zepto);
/**
 * @tableofcontents
 *
 * 1. editor
 *    1.1 create toolbar
 *    1.2 create preview
 *    1.3 action
 *    1.4 insert
 *    1.5 insert html
 *    1.6 insert codequote
 *    1.7 insert readmore
 *    1.8 format
 *    1.9 toggle
 *    1.10 get selection
 *    1.11 check selection
 *    1.12 post
 *    1.13 validate
 *    1.14 init
 * 2. init
 *
 * @since 2.4.0
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

		if (rs.modules.editor.options !== options)
		{
			options = $.extend({}, rs.modules.editor.options, options || {});
		}

		/* detect needed mode */

		if (rs.registry.loggedIn === rs.registry.token && rs.registry.firstParameter === 'admin')
		{
			options.toolbar = options.toolbar.backend;
			options.breakOnEnter = options.breakOnEnter.backend;
		}
		else
		{
			options.toolbar = options.toolbar.frontend;
			options.breakOnEnter = options.breakOnEnter.frontend;
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

				editor.toolbar = $('<div unselectable="on">').addClass(options.className.editorToolbar).appendTo(editor.container);

				/* append controls */

				for (var i in options.toolbar)
				{
					var name = options.toolbar[i],
						data = rs.modules.editor.controls[name],
						control = '';

					/* append toggle */

					if (name === 'toggle')
					{
						editor.controlToggle = control = $('<a title="' + data.title + '"></a>').addClass(options.className.editorControl + ' ' + options.className.editorSourceCode).appendTo(editor.toolbar);
					}

					/* append several controls */

					else if (typeof data === 'object')
					{
						control = $('<a title="' + data.title + '"></a>').addClass(options.className.editorControl + ' ' + name).appendTo(editor.toolbar);
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

								/* vibrate feedback */

								if (rs.support.vibrate && typeof options.vibrate === 'number')
								{
									navigator.vibrate(options.vibrate);
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

				editor.preview = $('<div contenteditable="true">' + editor.textarea.val() + '</div>').addClass(options.className.editorPreview).appendTo(editor.container);

				/* insert break on enter */

				if (options.breakOnEnter)
				{
					editor.preview.on('keydown', function (event)
					{
						if (event.which === 13)
						{
							if (rs.registry.myEngine === 'gecko')
							{
								document.execCommand('insertBrOnReturn', false, false);
							}
							else if (rs.registry.myEngine === 'trident')
							{
								editor.insertHTML('<br />');
								event.preventDefault();
							}
							else if (rs.registry.myEngine === 'webkit')
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
				try
				{
					document.execCommand(command, 0, 0);
				}

				/* alert dialog if no support */

				catch (exception)
				{
					$.fn.dialog(
					{
						message: rs.language._editor.browser_support_no + rs.language.point
					});
				}
			};

			/* @section 1.4 insert */

			editor.insert = function (command, message, value)
			{
				/* prompt dialog */

				$.fn.dialog(
				{
					type: 'prompt',
					message: message + rs.language.colon,
					value: value,
					callback: function (input)
					{
						/* create link without selection */

						if (command === 'createLink')
						{
							editor.insertHTML('<a href="' + input + '" title="' + input + '">' + input + '</a>');
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

			/* @section 1.6 insert codequote */

			editor.insertCodequote = function ()
			{
				if (editor.checkSelection())
				{
					editor.insertHTML('<codequote>' + editor.select() + '</codequote>');
				}
			};

			/* @section 1.7 insert readmore */

			editor.insertReadmore = function ()
			{
				editor.insertHTML('<readmore>');
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
					editor.preview.html(editor.textarea.val()).focus();
					editor.controlToggle.attr('title', rs.language._editor.source_code);
				}
				else
				{
					editor.mode = 1;
					editor.textarea.val(editor.preview.html()).focus();
					editor.controlToggle.attr('title', rs.language._editor.wysiwyg);
				}
				editor.controlToggle.toggleClass(options.className.editorSourceCode + ' ' + options.className.editorWysiwyg).nextAll(options.element.editorControl).toggle();
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
						message: rs.language._editor.select_text_first + rs.language.point
					});
				}
			};

			/* @section 1.12 post */

			editor.post = function ()
			{
				var html = editor.preview.html();

				if (html.length)
				{
					editor.textarea.val(html).trigger('change');
				}
			};

			/* @section 1.13 validate */

			editor.validate = function ()
			{
				editor.textarea.add(editor.preview).attr('data-related', 'editor').trigger('related');
			};

			/* @section 1.14 init */

			editor.init = function ()
			{
				/* create editor elements */

				editor.textarea.hide();
				editor.container = $('<div>').addClass(options.className.editor).insertBefore(editor.textarea);

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

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.editor.init)
		{
			$(rs.modules.editor.selector).editor(rs.modules.editor.options);
		}
	});
})(window.jQuery || window.Zepto);

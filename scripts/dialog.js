/**
 * @tableofcontents
 *
 * 1. dialog
 *    1.1 open
 *    1.2 listen
 *    1.3 close
 *    1.4 init
 * 2. confirm link
 * 3. prevent unload
 * 4. startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. dialog */

	$.fn.dialog = function (options)
	{
		/* extend options */

		if (r.plugins.dialog.options !== options)
		{
			options = $.extend({}, r.plugins.dialog.options, options || {});
		}

		/* detect needed mode */

		if (r.constants.LOGGED_IN === r.constants.TOKEN && r.constants.FIRST_PARAMETER === 'admin')
		{
			options.suffix = options.suffix.backend;
			r.flags.backend = true;
		}
		else
		{
			options.suffix = options.suffix.frontend;
		}

		var dialog = {};

		/* @section 1.1 open */

		dialog.open = function ()
		{
			/* append dialog elements */

			dialog.title.add(dialog.box).appendTo(dialog.container);

			/* message */

			if (options.message)
			{
				dialog.textMessage = $('<p>' + options.message + '</p>').appendTo(dialog.box);
			}

			/* prompt input */

			if (options.type === 'prompt')
			{
				dialog.fieldPrompt = $('<input type="text" value="' + options.value + '" />').addClass(options.className.fieldPrompt + (r.flags.backend ? options.suffix : '')).appendTo(dialog.box);
			}

			/* ok button */

			dialog.buttonOk.appendTo(dialog.box);

			/* cancel button */

			if (options.type === 'confirm' || options.type === 'prompt')
			{
				dialog.buttonCancel.appendTo(dialog.box);
			}

			/* append to body */

			dialog.container.add(dialog.overlay).appendTo('body');
			r.flags.modal = true;
		};

		/* @section 1.2 listen */

		dialog.listen = function ()
		{
			/* listen for click */

			dialog.buttonOk.on('click', function ()
			{
				if (typeof options.callback === 'function')
				{
					if (options.type === 'prompt')
					{
						var value = $.trim(dialog.fieldPrompt.val());

						if (value)
						{
							options.callback.call(this, value);
						}
					}
					else
					{
						options.callback.call(this);
					}
				}
			})

			/* close dialog */

			.add(dialog.buttonCancel).add(dialog.overlay).on('click', function ()
			{
				dialog.close();
			});

			/* listen for keydown */

			$(window).on('keydown', function (event)
			{
				if (event.which === 27)
				{
					dialog.close();
				}
			});
		};

		/* @section 1.3 close */

		dialog.close = function ()
		{
			dialog.container.add(dialog.overlay).remove();
			r.flags.modal = false;
		};

		/* @section 1.4 init */

		dialog.init = function ()
		{
			/* create dialog elements */

			dialog.overlay = $('<div>').addClass(options.className.dialogOverlay + options.suffix);
			dialog.container = $('<div>').addClass(options.className.dialog + options.suffix);
			dialog.title = $('<h3>' + l[options.type] + '</h3>').addClass(options.className.dialogTitle + options.suffix);
			dialog.box = $('<div>').addClass(options.className.dialogBox + options.suffix);
			dialog.buttonOk = $('<a>' + l.ok + '</a>').addClass(options.className.buttonOk + options.suffix);
			dialog.buttonCancel = $('<a>' + l.cancel + '</a>').addClass(options.className.buttonCancel + options.suffix);

			/* open and listen */

			if (!r.flags.modal)
			{
				dialog.open();
				dialog.listen();
			}
		};

		/* init as needed */

		if (r.constants.MY_BROWSER === 'msie' && r.constants.MY_BROWSER_VERSION < 7)
		{
			return false;
		}
		else
		{
			dialog.init();
		}
	};

	/* @section 2. confirm link */

	$.fn.confirmLink = function ()
	{
		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).on('click', function (event)
			{
				var link = $(this),
					url = link.attr('href');

				if (typeof url === 'string')
				{
					/* confirm dialog to continue */

					$.fn.dialog(
					{
						type: 'confirm',
						message: l.dialog_question + l.question_mark,
						callback: function ()
						{
							if (url.substr(0, 2) === '//' || url.substr(0, 7) === 'http://' || url.substr(0, 8) === 'https://')
							{
								window.location = url;
							}
							else
							{
								window.location = r.baseURL + url;
							}
						}
					});
					event.preventDefault();
				}
			});
		});
	};

	/* @section 3. prevent unload */

	$.fn.preventUnload = function (options)
	{
		/* extend options */

		if (r.plugins.preventUnload.options !== options)
		{
			options = $.extend({}, r.plugins.preventUnload.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			if (r.constants.ADMIN_PARAMETER === 'new' || r.constants.ADMIN_PARAMETER === 'edit')
			{
				/* listen for change */

				$(this).one('change', function ()
				{
					$('a').not(options.element.not).confirmLink();
				});
			}
		});
	};

	/* @section 4. startup */

	$(function ()
	{
		if (r.plugins.confirmLink.startup)
		{
			$(r.plugins.confirmLink.selector).confirmLink();

			/* dependency */

			if (r.plugins.preventUnload.startup)
			{
				$(r.plugins.preventUnload.selector).preventUnload(r.plugins.preventUnload.options);
			}
		}
	});
})(window.jQuery || window.Zepto);
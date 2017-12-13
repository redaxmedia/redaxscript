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
 * 4. init
 */

(function ($)
{
	'use strict';

	/** @section 1. dialog */

	$.fn.dialog = function (options)
	{
		/* extend options */

		if (rs.plugins.dialog.options !== options)
		{
			options = $.extend({}, rs.plugins.dialog.options, options || {});
		}

		/* detect needed mode */

		if (rs.registry.loggedIn === rs.registry.token && rs.registry.firstParameter === 'admin')
		{
			options.className = options.className.backend;
		}
		else
		{
			options.className = options.className.frontend;
		}

		var dialog = {};

		/** @section 1.1 open */

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
				dialog.fieldPrompt = $('<input type="text" value="' + options.value + '" />').addClass(options.className.fieldPrompt).appendTo(dialog.box);
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
			rs.flags.modal = true;
		};

		/** @section 1.2 listen */

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

		/** @section 1.3 close */

		dialog.close = function ()
		{
			dialog.container.add(dialog.overlay).remove();
			rs.flags.modal = false;
		};

		/** @section 1.4 init */

		dialog.init = function ()
		{
			/* create dialog elements */

			dialog.overlay = $('<div>').addClass(options.className.dialogOverlay);
			dialog.container = $('<div>').addClass(options.className.dialog);
			dialog.title = $('<h3>' + rs.language[options.type] + '</h3>').addClass(options.className.dialogTitle);
			dialog.box = $('<div>').addClass(options.className.dialogBox);
			dialog.buttonOk = $('<a>' + rs.language.ok + '</a>').addClass(options.className.buttonOk);
			dialog.buttonCancel = $('<a>' + rs.language.cancel + '</a>').addClass(options.className.buttonCancel);

			/* open and listen */

			if (!rs.flags.modal)
			{
				dialog.open();
				dialog.listen();
			}
		};

		/* init */

		dialog.init();
	};

	/** @section 2. confirm link */

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
						message: rs.language.dialog_question + rs.language.question_mark,
						callback: function ()
						{
							if (url.substr(0, 2) === '//' || url.substr(0, 7) === 'http://' || url.substr(0, 8) === 'https://')
							{
								window.location = url;
							}
							else
							{
								window.location = rs.baseURL + url;
							}
						}
					});
					event.preventDefault();
				}
			});
		});
	};

	/** @section 3. prevent unload */

	$.fn.preventUnload = function (options)
	{
		/* extend options */

		if (rs.plugins.preventUnload.options !== options)
		{
			options = $.extend({}, rs.plugins.preventUnload.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			if (rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit')
			{
				/* listen for change */

				$(this).one('change', function ()
				{
					$('a').not(options.element.not).confirmLink();
				});
			}
		});
	};

	/** @section 4. init */

	$(function ()
	{
		if (rs.plugins.confirmLink.init)
		{
			$(rs.plugins.confirmLink.selector).confirmLink();

			/* dependency */

			if (rs.plugins.preventUnload.init)
			{
				$(rs.plugins.preventUnload.selector).preventUnload(rs.plugins.preventUnload.options);
			}
		}
	});
})(window.jQuery);
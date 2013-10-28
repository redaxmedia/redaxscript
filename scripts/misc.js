/**
 * @tableofcontents
 *
 * 1. forward notification
 * 2. key shortcut
 * 3. startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. forward notification */

	$.fn.forwardNotification = function (options)
	{
		/* extend options */

		if (r.plugins.forwardNotification.options !== options)
		{
			options = $.extend({}, r.plugins.forwardNotification.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var link = $(this),
				url = link.attr('href');

			/* timeout enhanced forward */

			if (typeof url === 'string')
			{
				setTimeout(function ()
				{
					if (url.substr(0, 7) !== 'http://' && url.substr(0, 8) !== 'https://')
					{
						window.location.pathname = url;
					}
					else
					{
						window.location = url;
					}
				}, options.duration);
			}
		});
	};

	/* @section 2. key shortcut */

	$.fn.keyShortcut = function (options)
	{
		/* extend options */

		if (r.plugins.keyShortcut.options !== options)
		{
			options = $.extend({}, r.plugins.keyShortcut.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for keydown */

			$(this).on('keydown', function (event)
			{
				var adminDock = $(options.element.adminDock),
					adminPanel = $(options.element.adminPanel),
					buttonSubmit = $(options.element.buttonSubmit),
					buttonOk = $(options.element.buttonOk),
					buttonCancel = $(options.element.buttonCancel);

				if (event.ctrlKey && event.altKey)
				{
					/* trigger cancel action */

					if (event.which === 67)
					{
						buttonCancel.click();
					}

					/* toggle admin docks */

					else if (event.which === 68)
					{
						adminDock.toggle();
					}

					/* login and logout */

					else if (event.which === 76)
					{
						if (r.constants.LOGGED_IN === r.constants.TOKEN)
						{
							window.location.pathname = r.constants.REWRITE_ROUTE + r.plugins.keyShortcut.routes.logout;
						}
						else
						{
							window.location.pathname = r.constants.REWRITE_ROUTE + r.plugins.keyShortcut.routes.login;
						}
					}

					/* trigger ok action */

					else if (event.which === 79)
					{
						buttonOk.click();
					}

					/* toggle admin panel */

					else if (event.which === 80)
					{
						adminPanel.toggle();
					}

					/* trigger submit action */

					else if (event.which === 83)
					{
						buttonSubmit.click();
					}

					/* alert dialog if input incorrect */

					else if (event.which > 65 && event.which < 91 && event.which !== 69 && event.which !== 77 && event.which !== 81)
					{
						$.fn.dialog(
						{
							message: l.input_incorrect + l.point
						});
					}
				}
			});
		});
	};

	/* @section 3. startup */

	$(function ()
	{
		if (r.plugins.keyShortcut.startup)
		{
			$(r.plugins.keyShortcut.selector).keyShortcut(r.plugins.keyShortcut.options);
		}
		if (r.plugins.forwardNotification.startup)
		{
			$(r.plugins.forwardNotification.selector).forwardNotification(r.plugins.forwardNotification.options);
		}
	});
})(window.jQuery || window.Zepto);
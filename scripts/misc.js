/**
 * @tableofcontents
 *
 * 1. forward notification
 * 2. key shortcut
 * 3. startup
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
			/* trigger click after delay */

			$(this).delay(options.duration).queue(function ()
			{
				var link = $(this),
					url = link.attr('href');

				if (typeof url === 'string')
				{
					window.location = url;
				}
			});
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

					/* trigger ok action */

					else if (event.which === 79)
					{
						buttonOk.click();
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
		if (r.plugins.keyShortcut.startup && r.constants.LOGGED_IN === r.constants.TOKEN)
		{
			$(r.plugins.keyShortcut.selector).keyShortcut(r.plugins.keyShortcut.options);
		}
		if (r.plugins.forwardNotification.startup)
		{
			$(r.plugins.forwardNotification.selector).forwardNotification(r.plugins.forwardNotification.options);
		}
	});
})(r.library);
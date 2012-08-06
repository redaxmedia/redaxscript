(function ($)
{
	/* key shortcut */

	$.fn.keyShortcut = function (options)
	{
		/* extend options */

		if (r.plugin.keyShortcut.options !== options)
		{
			options = $.extend({}, r.plugin.keyShortcut.options, options || {});
		}

		/* listen for keydown */

		$(this).on('keydown', function (event)
		{
			var adminDock = $(options.element.adminDock),
				buttonCancel = $(options.element.buttonCancel),
				buttonOk = $(options.element.buttonOk),
				buttonSubmit = $(options.element.buttonSubmit);

			if (event.ctrlKey && event.altKey)
			{
				/* trigger cancle action */

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

				else if (event.which > 65 && event.which < 91)
				{
					$.fn.dialog(
					{
						type: 'alert',
						message: l.input_incorrect + l.point
					});
				}
			}
		});
	};

	/* forward notification */

	$.fn.forwardNotification = function (options)
	{
		/* extend options */

		if (r.plugin.forwardNotification.options !== options)
		{
			options = $.extend({}, r.plugin.forwardNotification.options, options || {});
		}

		var link = $(this);

		link.delay(options.duration).queue(function ()
		{
			link.click();
		});
	};
})(jQuery);

$(function ()
{
	/* startup */

	if (r.plugin.keyShortcut.startup && r.constant.LOGGED_IN === r.constant.TOKEN)
	{
		$(r.plugin.keyShortcut.selector).keyShortcut(r.plugin.keyShortcut.options);
	}
	if (r.plugin.forwardNotification.startup)
	{
		$(r.plugin.forwardNotification.selector).forwardNotification(r.plugin.forwardNotification.options);
	}
});
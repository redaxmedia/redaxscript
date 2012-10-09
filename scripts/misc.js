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

	/* forward notification */

	$.fn.forwardNotification = function (options)
	{
		/* extend options */

		if (r.plugin.forwardNotification.options !== options)
		{
			options = $.extend({}, r.plugin.forwardNotification.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* trigger click after delay */

			$(this).delay(options.duration).queue(function ()
			{
				$(this).click();
			});
		});
	};
})(jQuery);

jQuery(function ($)
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
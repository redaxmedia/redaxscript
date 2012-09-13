(function ($)
{
	/* admin dock */

	$.fn.adminDock = function (options)
	{
		/* extend options */

		if (r.plugin.adminDock.options !== options)
		{
			options = $.extend({}, r.plugin.adminDock.options, options || {});
		}

		var dock = $(this),
			dockLink = dock.find(options.element.dockLink);

		/* append description to docks */

		dock.append(options.element.dockDescriptionHTML);

		/* setup dock links */

		dockLink.each(function ()
		{
			var dockElement = $(this),
				dockElementText = dockElement.text(),
				dockDescription = dockElement.siblings(options.element.dockDescription);

			/* change on hover */

			dockElement.hover(function ()
			{
				dockDescription.text(dockElementText);
			}, function ()
			{
				dockDescription.text('');
			});
		});
	};

	/* admin panel */

	$.fn.adminPanel = function (options)
	{
		/* extend options */

		if (r.plugin.adminPanel.options !== options)
		{
			options = $.extend({}, r.plugin.adminPanel.options, options || {});
		}

		var panel = $(this),
			panelHeight = panel.height(),
			panelBox = panel.find(options.element.panelBox),
			panelBar = panel.find(options.element.panelBar),
			panelBarHeight = panelBar.height(),
			panelRelated = $(options.related);

		/* opened on admin */

		if (r.constant.FIRST_PARAMETER === 'admin')
		{
			panelRelated.css('margin-top', panelHeight);
		}

		/* else closed */

		else
		{
			panel.height(panelHeight / 2);
			panelBox.hide();
			panelRelated.css('margin-top', panelBarHeight);

			/* show on hover */

			panel.hover(function ()
			{
				panelBox.stop(1).show();
			}, function ()
			{
				panelBox.delay(options.duration).queue(function ()
				{
					$(this).hide();
				});
			});
		}
	};
})(jQuery);

jQuery(function ($)
{
	/* startup */

	if (r.plugin.adminDock.startup)
	{
		$(r.plugin.adminDock.selector).adminDock(r.plugin.adminDock.options);
	}
	if (r.plugin.adminPanel.startup)
	{
		$(r.plugin.adminPanel.selector).adminPanel(r.plugin.adminPanel.options);
	}
});
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

		/* return this */

		return this.each(function ()
		{
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

				/* listen for hover */

				dockElement.hover(function ()
				{
					dockDescription.text(dockElementText);
				}, function ()
				{
					dockDescription.text('');
				});
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

		/* return this */

		return this.each(function ()
		{
			var panel = $(this),
				panelHeight = panel.height(),
				panelBox = panel.find(options.element.panelBox),
				panelBar = panel.find(options.element.panelBar),
				panelBarHeight = panelBar.height(),
				panelRelated = $(options.related);

			/* open on admin */

			if (r.constant.FIRST_PARAMETER === 'admin')
			{
				panelRelated.css('margin-top', panelHeight);
			}

			/* else close */

			else
			{
				panel.height(panelHeight / 2);
				panelBox.hide();
				panelRelated.css('margin-top', panelBarHeight);

				/* listen for hover */

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
		});
	};

	/* startup */

	$(function ()
	{
		if (r.plugin.adminDock.startup)
		{
			$(r.plugin.adminDock.selector).adminDock(r.plugin.adminDock.options);
		}
		if (r.plugin.adminPanel.startup)
		{
			$(r.plugin.adminPanel.selector).adminPanel(r.plugin.adminPanel.options);
		}
	});
})(jQuery);
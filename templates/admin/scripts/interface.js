/**
 * @tableofcontents
 *
 * 1. admin dock
 * 2. admin panel
 * 3. startup
 */

(function ($)
{
	'use strict';

	/* @section 1. admin dock */

	$.fn.adminDock = function (options)
	{
		/* extend options */

		if (r.plugins.adminDock.options !== options)
		{
			options = $.extend({}, r.plugins.adminDock.options, options || {});
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

	/* @section 2. admin panel */

	$.fn.adminPanel = function (options)
	{
		/* extend options */

		if (r.plugins.adminPanel.options !== options)
		{
			options = $.extend({}, r.plugins.adminPanel.options, options || {});
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

			if (r.constants.FIRST_PARAMETER === 'admin')
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

	/* @section 3. startup */

	$(function ()
	{
		if (r.plugins.adminDock.startup)
		{
			$(r.plugins.adminDock.selector).adminDock(r.plugins.adminDock.options);
		}
		if (r.plugins.adminPanel.startup)
		{
			$(r.plugins.adminPanel.selector).adminPanel(r.plugins.adminPanel.options);
		}
	});
})(r.library);
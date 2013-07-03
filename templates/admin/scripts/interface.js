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

				dockElement.on('mouseenter mouseleave touchstart touchend', function (event)
				{
					/* handle mouseenter and touchstart */

					if (event.type === 'mouseenter' || event.type === 'touchstart')
					{
						dockDescription.text(dockElementText);
					}

					/* else handle mouseleave and touchend */

					else
					{
						dockDescription.text('');
					}
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
			var panelList = $(this),
				panelItem = panelList.find('li'),
				panelChildren = panelItem.children('ul'),
				timeout;

			/* listen for click and touchover */

			panelItem.on('click touchover', function ()
			{
				var thatItem = $(this),
					thatChildren = thatItem.children('ul');

				/* handle click and touchover */

				panelChildren.stop(0).slideUp(options.duration);
				thatChildren.stop(0).slideDown(options.duration);
			})

			/* listen for mouseenter and mouseleave */

			panelList.on('mouseenter mouseleave', function (event)
			{
				if (event.type === 'mouseleave')
				{
					timeout = setTimeout(function () {
						panelChildren.stop(0).slideUp(options.duration);
					}, options.timeout);
				}

				/* else clear timeout */

				else
				{
					clearTimeout(timeout);
				}
			});
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
})(window.jQuery || window.Zepto);
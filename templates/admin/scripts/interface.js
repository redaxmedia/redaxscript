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

				/* listen for mouse and touch events */

				dockElement.on('mouseenter mouseleave touchstart touchend', function (event)
				{
					if (event.type === 'mouseenter' || event.type === 'touchstart')
					{
						dockDescription.text(dockElementText);
					}
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
				panelChildren = panelItem.children('ul');;
			
			/* stop propagation */

			panelChildren.on('click touchstart', function (event)
			{
				event.stopPropagation();
			});

			/* listen for click and touchstart */

			panelItem.on('click touchstart', function ()
			{
				var thatItem = $(this),
					thatChildren = thatItem.children('ul');

				thatChildren.toggle();
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
			$(r.plugins.adminPanel.selector).adminPanel();
		}
	});
})(window.jQuery || window.Zepto);
/**
 * @tableofcontents
 *
 * 1. admin dock
 * 2. admin panel
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
				var dockLink = $(this),
					dockText = dockLink.text(),
					dockDescription = dockLink.siblings(options.element.dockDescription);

				dockLink.on('mouseenter mouseleave touchstart touchend', function (event)
				{
					/* handle mouseenter and touchstart */

					if (event.type === 'mouseenter' || event.type === 'touchstart')
					{
						dockDescription.text(dockText);
					}

					/* else handle mouseleave and touchend */

					else
					{
						dockDescription.empty();
					}

					/* haptic feedback */

					if (event.type === 'touchstart' && r.support.vibrate && typeof options.vibrate === 'number')
					{
						window.navigator.vibrate(options.vibrate);
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
				panelItem = panelList.children('li'),
				panelItemAll = panelList.find('li'),
				panelChildren = panelItemAll.children('ul'),
				panelLink = panelList.find('a'),
				timeoutEnter = '',
				timeoutLeave = '';

			/* listen for click and mouseenter  */

			panelItemAll.on('click mouseenter', function (event)
			{
				var thatItem = $(this),
					thatChildren = thatItem.children('ul'),
					thatClosest = thatItem.closest(options.element.panelItem),
					thatRelated = thatClosest.find('ul'),
					panelFloat = panelItem.css('float');

				/* exception for narrow panel */

				if (panelFloat === 'none')
				{
					/* premature teminate mouseenter */

					if (event.type === 'mouseenter')
					{
						thatItem.trigger('enter');
						return false;
					}

					/* else show nested list */

					else
					{
						thatChildren.find('ul').show();
					}
				}

				/* timeout enhanced slide */

				clearTimeout(timeoutEnter);
				timeoutEnter = setTimeout(function ()
				{
					panelChildren.not(thatRelated).stop(0).slideUp(options.duration);
					thatChildren.stop(0).slideDown(options.duration);

					/* active item */

					panelItemAll.removeClass('item_active');
					thatItem.addClass('item_active');
				}, options.duration);
			});

			/* listen for enter and leave */

			panelList.on('enter mouseenter mouseleave', function (event)
			{
				/* timeout enhanced slide */

				if (event.type === 'mouseleave')
				{
					clearTimeout(timeoutEnter);
					timeoutLeave = setTimeout(function ()
					{
						panelChildren.stop(0).slideUp(options.duration, function ()
						{
							panelItemAll.removeClass('item_active');
						});
					}, options.timeout);
				}

				/* else clear timeout */

				else
				{
					clearTimeout(timeoutLeave);
				}
			});

			/* listen for click */

			panelLink.on('click', function ()
			{
				/* haptic feedback */

				if (r.support.vibrate && typeof options.vibrate === 'number')
				{
					window.navigator.vibrate(options.vibrate);
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
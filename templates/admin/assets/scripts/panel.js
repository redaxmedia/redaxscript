/**
 * @tableofcontents
 *
 * 1. panel
 * 2. init
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. panel */

	$.fn.panel = function (options)
	{
		/* extend options */

		if (rs.plugins.panel.options !== options)
		{
			options = $.extend({}, rs.plugins.panel.options, options || {});
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

					panelItemAll.removeClass('rs-admin-item-active');
					thatItem.addClass('rs-admin-item-active');
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
							panelItemAll.removeClass('rs-admin-item-active');
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

				if (rs.support.vibrate && typeof options.vibrate === 'number')
				{
					window.navigator.vibrate(options.vibrate);
				}
			});
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.plugins.panel.init)
		{
			$(rs.plugins.panel.selector).panel(rs.plugins.panel.options);
		}
	});
})(window.jQuery);
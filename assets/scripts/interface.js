/**
 * @tableofcontents
 *
 * 1. accordion
 * 2. tab
 * 3. init
 */

(function ($)
{
	'use strict';

	/** @section 1. accordion */

	$.fn.accordion = function (options)
	{
		/* extend options */

		if (rs.plugins.accordion.options !== options)
		{
			options = $.extend({}, rs.plugins.accordion.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var accordion = $(this),
				accordionSet = accordion.find(options.element.accordionSet),
				accordionTitle = accordion.find(options.element.accordionTitle),
				accordionBox = accordion.find(options.element.accordionBox),
				prefix = accordion.filter('[class^="rs-admin"]').length ? 'rs-admin-' : 'rs-';

			/* show active box */

			accordionBox.filter('.' + prefix + 'js-box-active').show();

			/* listen for click */

			accordionTitle.on('click', function ()
			{
				var accordionTitleActive = $(this),
					accordionSetActive = accordionTitleActive.closest(accordionSet),
					accordionBoxActive = accordionSetActive.find(accordionBox);

				/* toggle active class */

				accordionSet.removeClass(prefix + 'js-set-active ' + prefix + 'set-active').filter(accordionSetActive).addClass(prefix + 'js-set-active ' + prefix + 'set-active');
				accordionTitle.removeClass(prefix + 'js-title-active ' + prefix +  'title-active').filter(accordionTitleActive).addClass(prefix + 'js-title-active ' + prefix + 'title-active');

				/* slide boxes */

				accordionBox.stop(1).not(accordionBoxActive).slideUp(options.duration).removeClass(prefix + 'js-box-active  ' + prefix + 'box-active');
				accordionBoxActive.slideDown(options.duration).addClass(prefix + 'js-box-active  ' + prefix + 'box-active');
			});

			/* show error */

			accordion.on('error', function ()
			{
				var accordionSetError = accordionSet.has('.' + prefix + 'js-note-error').first(),
					accordionTitleError = accordionSetError.find(accordionTitle);

				accordionTitleError.click();
			});
		});
	};

	/** @section 2. tab */

	$.fn.tab = function (options)
	{
		/* extend options */

		if (rs.plugins.tab.options !== options)
		{
			options = $.extend({}, rs.plugins.tab.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var tab = $(this),
				tabItem = tab.find(options.element.tabItem),
				tabSet = tab.find(options.element.tabSet),
				prefix = tab.filter('[class^="rs-admin"]').length ? 'rs-admin-' : 'rs-';

			/* show active set */

			tabSet.filter('.' + prefix + 'js-set-active').show();

			/* listen for click */

			tabItem.on('click', function (event)
			{
				var tabItemActive = $(this),
					tabNameActive = tabItemActive.children('a').attr('href').split('#')[1],
					tabSetActive = tabSet.filter('#' + tabNameActive);

				/* toggle active class */

				tabItem.removeClass(prefix + 'js-item-active ' + prefix + 'item-active').filter(tabItemActive).addClass(prefix + 'js-item-active ' + prefix + 'item-active');
				tabSet.removeClass(prefix + 'js-set-active ' + prefix + 'set-active').filter(tabSetActive).addClass(prefix + 'js-set-active ' + prefix + 'set-active');
				event.preventDefault();
			});

			/* show error */

			tab.on('error', function ()
			{
				var tabNameError = tabSet.has('.' + prefix + 'js-note-error').first().attr('id'),
					tabItemError = tabItem.find('a[href*="' + tabNameError + '"]');

				tabItemError.click();
			});
		});
	};

	/** @section 3. init */

	$(function ()
	{
		if (rs.plugins.accordion.init)
		{
			$(rs.plugins.accordion.selector).accordion(rs.plugins.accordion.options);
		}
		if (rs.plugins.tab.init)
		{
			$(rs.plugins.tab.selector).tab(rs.plugins.tab.options);
		}
	});
})(window.jQuery);
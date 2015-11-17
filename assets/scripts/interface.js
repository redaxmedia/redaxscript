/**
 * @tableofcontents
 *
 * 1. accordion
 * 2. dropdown
 * 3. tab
 * 4. init
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. accordion */

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
				accordionBox = accordion.find(options.element.accordionBox);

			/* show active box */

			accordionBox.filter('.rs-js-box-active').show();

			/* listen for click */

			accordionTitle.on('click', function ()
			{
				var accordionTitleActive = $(this),
					accordionSetActive = accordionTitleActive.closest(accordionSet),
					accordionBoxActive = accordionSetActive.find(accordionBox);

				/* toggle active class */

				accordionSet.removeClass('rs-js-set-active rs-set-active').filter(accordionSetActive).addClass('rs-js-set-active rs-set-active');
				accordionTitle.removeClass('rs-js-title-active rs-title-active').filter(accordionTitleActive).addClass('rs-js-title-active rs-title-active');

				/* slide boxes */

				accordionBox.stop(1).not(accordionBoxActive).slideUp(options.duration).removeClass('rs-js-box-active rs-box-active');
				accordionBoxActive.slideDown(options.duration).addClass('rs-js-box-active rs-box-active');
			});

			/* show error */

			accordion.on('error', function ()
			{
				var accordionSetError = accordionSet.has('.rs-js-note-error').first(),
					accordionTitleError = accordionSetError.find(accordionTitle);

				accordionTitleError.click();
			});
		});
	};

	/* @section 2. dropdown */

	$.fn.dropdown = function (options)
	{
		/* extend options */

		if (rs.plugins.accordion.options !== options)
		{
			options = $.extend({}, rs.plugins.dropdown.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var dropdown = $(this),
				dropdownRelated = dropdown.find(options.element.item),
				timeout = '';

			/* listen for touchstart and touchend */

			dropdownRelated.on('touchstart touchend', function (event)
			{
				var dropdownItem = $(this);

				/* if touchstart */

				if (event.type === 'touchstart')
				{
					dropdownItem.addClass('item-touch');
				}

				/* else timeout enhanced touchend */

				else if (event.type === 'touchend')
				{
					clearTimeout(timeout);
					timeout = setTimeout(function ()
					{
						dropdownItem.removeClass('item-touch');
					}, options.duration);
				}
			});
		});
	};

	/* @section 3. tab */

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
				tabSet = tab.find(options.element.tabSet);

			/* show active set */

			tabSet.filter('.rs-js-set-active').show();

			/* listen for click */

			tabItem.on('click', function (event)
			{
				var tabItemActive = $(this),
					tabNameActive = tabItemActive.children('a').attr('href').split('#')[1],
					tabSetActive = tabSet.filter('#' + tabNameActive);

				/* toggle active class */

				tabItem.removeClass('rs-js-item-active rs-item-active').filter(tabItemActive).addClass('rs-js-item-active rs-item-active');
				tabSet.removeClass('rs-js-set-active rs-set-active').filter(tabSetActive).addClass('rs-js-set-active rs-set-active');
				event.preventDefault();
			});

			/* show error */

			tab.on('error', function ()
			{
				var tabNameError = tabSet.has('.rs-js-note-error').first().attr('id'),
					tabItemError = tabItem.find('a[href*="' + tabNameError + '"]');

				tabItemError.click();
			});
		});
	};

	/* @section 4. init */

	$(function ()
	{
		if (rs.plugins.accordion.init)
		{
			$(rs.plugins.accordion.selector).accordion(rs.plugins.accordion.options);
		}
		if (rs.plugins.dropdown.init)
		{
			$(rs.plugins.dropdown.selector).dropdown(rs.plugins.dropdown.options);
		}
		if (rs.plugins.tab.init)
		{
			$(rs.plugins.tab.selector).tab(rs.plugins.tab.options);
		}
	});
})(window.jQuery || window.Zepto);

/**
 * @tableofcontents
 *
 * 1. accordion
 * 2. dropdown
 * 3. tab
 * 4. startup
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

		if (r.plugins.accordion.options !== options)
		{
			options = $.extend({}, r.plugins.accordion.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var accordion = $(this),
				accordionSet = accordion.find(options.element.accordionSet),
				accordionTitle = accordion.find(options.element.accordionTitle),
				accordionBox = accordion.find(options.element.accordionBox);

			/* show active box */

			accordionBox.filter('.js_box_active').show();

			/* listen for click */

			accordionTitle.on('click', function ()
			{
				var accordionTitleActive = $(this),
					accordionSetActive = accordionTitleActive.closest(accordionSet),
					accordionBoxActive = accordionSetActive.find(accordionBox);

				/* toggle active class */

				accordionSet.removeClass('js_set_active set_active').filter(accordionSetActive).addClass('js_set_active set_active');
				accordionTitle.removeClass('js_title_active title_active').filter(accordionTitleActive).addClass('js_title_active title_active');

				/* slide boxes */

				accordionBox.stop(1).not(accordionBoxActive).slideUp(options.duration).removeClass('js_box_active box_active');
				accordionBoxActive.slideDown(options.duration).addClass('js_box_active box_active');
			});

			/* show error */

			accordion.on('error', function ()
			{
				var accordionSetError = accordionSet.has('.js_note_error').first(),
					accordionTitleError = accordionSetError.find(accordionTitle);

				accordionTitleError.click();
			});
		});
	};

	/* @section 2. dropdown */

	$.fn.dropdown = function (options)
	{
		/* extend options */

		if (r.plugins.accordion.options !== options)
		{
			options = $.extend({}, r.plugins.dropdown.options, options || {});
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
					dropdownItem.addClass('item_touch');
				}

				/* else timeout enhanced touchend */

				else if (event.type === 'touchend')
				{
					clearTimeout(timeout);
					timeout = setTimeout(function ()
					{
						dropdownItem.removeClass('item_touch');
					}, options.duration);
				}
			});
		});
	};

	/* @section 3. tab */

	$.fn.tab = function (options)
	{
		/* extend options */

		if (r.plugins.tab.options !== options)
		{
			options = $.extend({}, r.plugins.tab.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var tab = $(this),
				tabItem = tab.find(options.element.tabItem),
				tabSet = tab.find(options.element.tabSet);

			/* show active set */

			tabSet.filter('.js_set_active').show();

			/* listen for click */

			tabItem.on('click', function (event)
			{
				var tabItemActive = $(this),
					tabNameActive = tabItemActive.children('a').attr('href').split('#')[1],
					tabSetActive = tabSet.filter('#' + tabNameActive);

				/* toggle active class */

				tabItem.removeClass('js_item_active item_active').filter(tabItemActive).addClass('js_item_active item_active');
				tabSet.removeClass('js_set_active set_active').filter(tabSetActive).addClass('js_set_active set_active');
				event.preventDefault();
			});

			/* show error */

			tab.on('error', function ()
			{
				var tabNameError = tabSet.has('.js_note_error').first().attr('id'),
					tabItemError = tabItem.find('a[href*="' + tabNameError + '"]');

				tabItemError.click();
			});
		});
	};

	/* @section 4. startup */

	$(function ()
	{
		if (r.plugins.accordion.startup)
		{
			$(r.plugins.accordion.selector).accordion(r.plugins.accordion.options);
		}
		if (r.plugins.dropdown.startup && r.support.touch)
		{
			$(r.plugins.dropdown.selector).dropdown(r.plugins.dropdown.options);
		}
		if (r.plugins.tab.startup)
		{
			$(r.plugins.tab.selector).tab(r.plugins.tab.options);
		}
	});
})(window.jQuery || window.Zepto);

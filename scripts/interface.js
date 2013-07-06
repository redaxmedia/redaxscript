/**
 * @tableofcontents
 *
 * 1. accordion
 * 2. dropdown
 * 3. tab
 * 4. startup
 *
 * @since 2.0
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
			var accordionTitle = $(this),
				accordion = accordionTitle.closest(options.element.accordion),
				accordionBox = accordion.find(options.element.accordionBox),
				accordionForm = accordion.closest('form');

			/* show active accordion box */

			accordion.find(options.element.accordionSet).filter('.js_set_active').children(options.element.accordionBox).show();

			/* listen for click and touchstart */

			$(this).on('click touchstart', function ()
			{
				var accordionTitleActive = $(this),
					accordion = accordionTitleActive.closest(options.element.accordion),
					accordionSet = accordion.find(options.element.accordionSet),
					accordionSetActive = accordionTitleActive.closest(options.element.accordionSet),
					accordionTitle = accordion.find(options.element.accordionTitle),
					accordionBox = accordion.find(options.element.accordionBox),
					accordionBoxActive = accordionTitleActive.next(options.element.accordionBox);

				/* remove active classes */

				accordionTitle.not(accordionTitleActive).removeClass('js_title_active title_active');
				accordionSet.not(accordionSetActive).removeClass('js_set_active set_active');

				/* slide accordion box */

				accordionBox.not(accordionBoxActive).stop(1).slideUp(options.duration);
				if (accordionBoxActive.is(':hidden'))
				{
					accordionBoxActive.stop(1).slideDown(options.duration);

					/* add active classes */

					accordionTitleActive.addClass('js_title_active title_active');
					accordionSetActive.addClass('js_set_active set_active');
				}
			});

			/* prevent tab for last children */

			accordionBox.children(':last-child').on('keydown', function (event)
			{
				if (event.which === 9)
				{
					event.preventDefault();
				}
			});

			/* show related set on validation error */

			accordionForm.on('error', function ()
			{
				$(this).find(options.element.accordionSet).has('.js_note_error').first().children(options.element.accordionTitle).click();
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
				dropdownRelated = dropdown.find(options.related),
				timeout;

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
			var url = window.location.href.replace(r.baseURL, ''),
				tabList = $(options.element.tabList),
				tabBox = $(options.element.tabBox),
				tabSet = tabBox.find(options.element.tabSet),
				tabForm = tabBox.closest('form');

			/* show first tab set */

			tabBox.height('auto').each(function ()
			{
				$(this).find(options.element.tabSet).first().addClass('js_set_active set_active');
			});

			/* listen for click and touchstart */

			$(this).on('click touchstart', function (event)
			{
				var tabItem = $(this),
					tabList = tabItem.closest(options.element.tabList),
					tabListChildren = tabList.children(),
					tabNameRelated = tabItem.children('a').attr('href').split('#')[1],
					tabSetRelated = $('#' + tabNameRelated),
					tabSetSiblings = tabSetRelated.siblings(options.element.tabSet);

				if (tabNameRelated)
				{
					/* remove active classes */

					tabListChildren.removeClass('js_item_active item_active');
					tabSetSiblings.removeClass('js_set_active set_active');

					/* add active classes */

					tabItem.addClass('js_item_active item_active');
					tabSetRelated.addClass('js_set_active set_active');
				}
				event.preventDefault();
			});

			/* click tab depending on location href */

			tabList.find('a[href="' + url + '"]').click();

			/* prevent tab for last children */

			tabSet.children().children(':last-child').on('keydown', function (event)
			{
				if (event.which === 9)
				{
					event.preventDefault();
				}
			});

			/* show related tab on validation error */

			tabForm.on('error', function ()
			{
				var id = $(this).find(options.element.tabSet).has('.js_note_error').first().attr('id');

				tabList.find('a[href*="' + r.constants.FULL_ROUTE + '#' + id + '"]').click();
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
			$(r.plugins.dropdown.selector).dropdown();
		}
		if (r.plugins.tab.startup)
		{
			$(r.plugins.tab.selector).tab(r.plugins.tab.options);
		}
	});
})(window.jQuery || window.Zepto);
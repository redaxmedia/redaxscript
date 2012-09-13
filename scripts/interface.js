(function ($)
{
	/* accordion */

	$.fn.accordion = function (options)
	{
		/* extend options */

		if (r.plugin.accordion.options !== options)
		{
			options = $.extend({}, r.plugin.accordion.options, options || {});
		}

		var accordionTitle = $(this),
			accordion = accordionTitle.closest(options.element.accordion),
			accordionBox = accordion.find(options.element.accordionBox),
			accordionForm = accordion.closest('form');

		/* show active accordion box */

		$(options.element.accordionSet).filter('.js_set_active').children(options.element.accordionBox).show();

		/* listen for click */

		$(this).click(function ()
		{
			var accordionTitleActive = $(this),
				accordion = accordionTitleActive.closest(options.element.accordion),
				accordionTitle = accordion.find(options.element.accordionTitle),
				accordionSet = accordion.find(options.element.accordionSet),
				accordionSetActive = accordionTitleActive.closest(options.element.accordionSet),
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

		/* prevent next tab for last children */

		accordionBox.children(':last-child').on('keydown', function (event)
		{
			if (event.which === 9)
			{
				return false;
			}
		});

		/* show related set on validation error */

		accordionForm.on('error', function ()
		{
			$(this).find(options.element.accordionSet).has('.js_note_error').first().children(options.element.accordionTitle).click();
		});
	};

	/* dropdown */

	$.fn.dropdown = function ()
	{
		$(this).each(function ()
		{
			var dropdown = $(this),
				dropdownChildren = dropdown.children();

			/* handle touch events */

			dropdownChildren.on('touchstart touchend', function (event)
			{
				var dropdownRelated = $(this).children();

				if (event.type === 'touchstart')
				{
					dropdownRelated.addClass('item_touch');
				}
				else if (event.type === 'touchend')
				{
					dropdownRelated.removeClass('item_touch');
				}
			});
		});
	};

	/* tab */

	$.fn.tab = function (options)
	{
		/* extend options */

		if (r.plugin.tab.options !== options)
		{
			options = $.extend({}, r.plugin.tab.options, options || {});
		}

		var string = window.location.href.replace(r.baseURL, ''),
			tabList = $(options.element.tabList),
			tabBox = $(options.element.tabBox),
			tabSet = tabBox.find(options.element.tabSet),
			tabForm = tabBox.closest('form');

		/* show first tab set */

		tabSet.hide();
		tabBox.height('auto').each(function ()
		{
			$(this).find(options.element.tabSet).first().addClass('js_set_active set_active').show();
		});

		/* click on tab */

		$(this).click(function ()
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
				tabSetSiblings.removeClass('js_set_active set_active').hide();

				/* add active classes */

				tabItem.addClass('js_item_active item_active');
				tabSetRelated.addClass('js_set_active set_active').show();
			}
			return false;
		});

		/* click tab depending on location href */

		tabList.find('a[href="' + string + '"]').click();

		/* prevent next tab for last children */

		tabSet.children().children(':last-child').on('keydown', function (event)
		{
			if (event.which === 9)
			{
				return false;
			}
		});

		/* show related tab on validation error */

		tabForm.on('error', function ()
		{
			var id = $(this).find(options.element.tabSet).has('.js_note_error').first().attr('id');

			tabList.find('a[href*="' + r.constant.FULL_STRING + '#' + id + '"]').click();
		});
	};
})(jQuery);

$(function ()
{
	/* startup */

	if (r.plugin.accordion.startup)
	{
		$(r.plugin.accordion.selector).accordion(r.plugin.accordion.options);
	}
	if (r.plugin.dropdown.startup && r.constant.MY_MOBILE)
	{
		$(r.plugin.dropdown.selector).dropdown();
	}
	if (r.plugin.tab.startup)
	{
		$(r.plugin.tab.selector).tab(r.plugin.tab.options);
	}
});
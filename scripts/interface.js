(function ($)
{
	/* accordion */

	$.fn.accordion = function (options)
	{
		/* extend options */

		if (r.plugin.accordion.options != options)
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
			if (event.which == 9)
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

	$.fn.dropDown = function ()
	{
		$(this).each(function ()
		{
			var dropDown = $(this),
				dropDownChildren = dropDown.children();

			/* handle touch events */

			dropDownChildren.on('touchstart touchend', function (event)
			{
				var dropDownRelated = $(this).children();

				if (event.type == 'touchstart')
				{
					dropDownRelated.addClass('item_touch');
				}
				else if (event.type == 'touchend')
				{
					dropDownRelated.removeClass('item_touch');
				}
			});
		});
	};

	/* tab menue */

	$.fn.tabMenue = function (options)
	{
		/* extend options */

		if (r.plugin.tabMenue.options != options)
		{
			options = $.extend({}, r.plugin.tabMenue.options, options || {});
		}

		var string = location.href.replace(r.baseURL, ''),
			tabMenueList = $(options.element.tabMenueList),
			tabMenueBox = $(options.element.tabMenueBox),
			tabMenueSet = tabMenueBox.find(options.element.tabMenueSet),
			tabMenueForm = tabMenueBox.closest('form');

		/* show first tabmenue set */

		tabMenueSet.hide();
		tabMenueBox.height('auto').each(function ()
		{
			$(this).find(options.element.tabMenueSet).first().addClass('js_set_active set_active').show();
		});

		/* click on tab */

		$(this).click(function ()
		{
			var tabMenueItem = $(this),
				tabMenueList = tabMenueItem.closest(options.element.tabMenueList),
				tabMenueListChildren = tabMenueList.children(),
				tabMenueNameRelated = tabMenueItem.children('a').attr('href').split('#')[1],
				tabMenueSetRelated = $('#' + tabMenueNameRelated),
				tabMenueSetSiblings = tabMenueSetRelated.siblings(options.element.tabMenueSet);

			if (tabMenueNameRelated)
			{
				/* remove active classes */

				tabMenueListChildren.removeClass('js_item_active item_active');
				tabMenueSetSiblings.removeClass('js_set_active set_active').hide();

				/* add active classes */

				tabMenueItem.addClass('js_item_active item_active');
				tabMenueSetRelated.addClass('js_set_active set_active').show();
			}
			return false;
		});

		/* click tab depending on location href */

		tabMenueList.find('a[href="' + string + '"]').click();

		/* prevent next tab for last children */

		tabMenueSet.children().children(':last-child').on('keydown', function (event)
		{
			if (event.which == 9)
			{
				return false;
			}
		});

		/* show related tab on validation error */

		tabMenueForm.on('error', function ()
		{
			var id = $(this).find(options.element.tabMenueSet).has('.js_note_error').first().attr('id');

			tabMenueList.find('a[href*="' + r.constant.FULL_STRING + '#' + id + '"]').click();
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
	if (r.plugin.dropDown.startup && r.constant.MY_MOBILE)
	{
		$(r.plugin.dropDown.selector).dropDown();
	}
	if (r.plugin.tabMenue.startup)
	{
		$(r.plugin.tabMenue.selector).tabMenue(r.plugin.tabMenue.options);
	}
});
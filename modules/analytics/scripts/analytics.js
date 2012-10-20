(function ($)
{
	'use strict';

	/* analytics */

	$.fn.analytics = function (options)
	{
		/* extend options */

		if (r.module.analytics.options !== options)
		{
			options = $.extend({}, r.module.analytics.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* create tracker */

			if (options.id && options.url)
			{
				r.module.analytics.tracker = _gat._createTracker(options.id);
				r.module.analytics.tracker._setDomainName(options.url);
				r.module.analytics.tracker._initData();
				r.module.analytics.tracker._trackPageview();

				/* listen for click */

				$(this).one('click', function ()
				{
					var trigger = $(this),
						category = trigger.data('category'),
						action = trigger.data('action'),
						label = trigger.data('label'),
						value = trigger.data('value'),
						string = category + ', ' + action;

					if (category && action)
					{
						/* extend string */

						if (label)
						{
							string += ', ' + label;
						}
						if (value)
						{
							string += ', ' + value;
						}
						r.module.analytics.tracker._trackEvent(string);
					}
				});
			}
		});
	};

	/* startup */

	$(function ()
	{
		if (r.module.analytics.startup && r.constant.LOGGED_IN !== r.constant.TOKEN && typeof _gat === 'object')
		{
			$(r.module.analytics.selector).analytics(r.module.analytics.options);
		}
	});
})(jQuery);
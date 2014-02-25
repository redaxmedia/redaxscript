/**
 * @tableofcontents
 *
 * 1. analytics
 * 2. startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. analytics */

	$.fn.analytics = function (options)
	{
		/* extend options */

		if (r.modules.analytics.options !== options)
		{
			options = $.extend({}, r.modules.analytics.options, options || {});
		}

		/* create tracker */

		if (options.id && options.url)
		{
			_gaq.push(
			[
				'_setAccount',
				options.id
			],
			[
				'_setDomainName',
				options.url
			],
			[
				'_trackPageview'
			]);
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).on('click', function ()
			{
				var trigger = $(this),
					category = trigger.data('category'),
					action = trigger.data('action'),
					label = trigger.data('label');

				/* track event */

				if (category && action && label)
				{
					_gaq.push(
					[
						'_trackEvent',
						String(category),
						String(action),
						String(label)
					]);
				}
			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.analytics.startup && r.constants.LOGGED_IN !== r.constants.TOKEN && typeof _gaq === 'object')
		{
			$(r.modules.analytics.selector).analytics(r.modules.analytics.options);
		}
	});
})(window.jQuery || window.Zepto);
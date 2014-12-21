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

		if (rxs.modules.analytics.options !== options)
		{
			options = $.extend({}, rxs.modules.analytics.options, options || {});
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
				'_gat._anonymizeIp'
			],
			[
				'_trackPageview'
			]);
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).one('click', function ()
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
		if (rxs.modules.analytics.startup && rxs.constants.LOGGED_IN !== rxs.constants.TOKEN && typeof _gaq === 'object')
		{
			$(rxs.modules.analytics.selector).analytics(rxs.modules.analytics.options);
		}
	});
})(window.jQuery || window.Zepto);

/**
 * @tableofcontents
 *
 * 1. analytics
 * 2. init
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, ga)
{
	'use strict';

	/* @section 1. analytics */

	$.fn.analytics = function (options)
	{
		/* extend options */

		if (rs.modules.analytics.options !== options)
		{
			options = $.extend({}, rs.modules.analytics.options, options || {});
		}

		/* create tracker */

		if (options.id && options.cookieDomain)
		{
			ga('create', options.id,
			{
				cookieDomain: options.cookieDomain,
				anonymizeIp: options.anonymizeIp
			});
			ga('send', 'pageview');
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for click */

			$(this).one('click', function ()
			{
				var target = $(this),
					category = String(target.data('category')),
					action = String(target.data('action')),
					label = String(target.data('label'));

				/* track event */

				if (category && action && label)
				{
					ga('send',
					{
						hitType: 'event',
						eventCategory: category,
						eventAction: action,
						eventLabel: label
					});
				}
			});
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.analytics.init && rs.modules.analytics.dependency)
		{
			$(rs.modules.analytics.selector).analytics(rs.modules.analytics.options);
		}
	});
})(window.jQuery || window.Zepto, window.ga);

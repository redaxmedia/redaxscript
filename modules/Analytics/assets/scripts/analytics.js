/**
 * @tableofcontents
 *
 * 1. analytics
 * 2. init
 */

(function ($, ga)
{
	'use strict';

	/** @section 1. analytics */

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
					label = target.data('label') ? String(target.data('label')) : null,
					value = target.data('value') ? Number(target.data('value')) : null;

				/* track event */

				if (category && action)
				{
					ga('send',
					{
						hitType: 'event',
						eventCategory: category,
						eventAction: action,
						eventLabel: label,
						eventValue: value
					});
				}
			});
		});
	};

	/** @section 2. init */

	$(function ()
	{
		if (rs.modules.analytics.init && rs.modules.analytics.dependency)
		{
			$(rs.modules.analytics.selector).analytics(rs.modules.analytics.options);
		}
	});
})(window.jQuery, window.ga);

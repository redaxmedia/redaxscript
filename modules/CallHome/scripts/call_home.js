/**
 * @tableofcontents
 *
 * 1. call home
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

	/* @section 1. call home */

	$.fn.callHome = function (options)
	{
		/* extend options */

		if (rxs.modules.callHome.options !== options)
		{
			options = $.extend({}, rxs.modules.callHome.options, options || {});
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

			/* track event */

			_gaq.push(
			[
				'_trackEvent',
				String('call-home'),
				String(rxs.version),
				String(rxs.baseURL)
			]);
		}
	};

	/* @section 2. startup */

	$(function ()
	{
		if (rxs.modules.callHome.startup && rxs.constants.LOGGED_IN === rxs.constants.TOKEN && rxs.constants.FIRST_PARAMETER === 'admin' && !rxs.constants.ADMIN_PARAMETER && typeof _gaq === 'object')
		{
			$.fn.callHome(rxs.modules.callHome.options);
		}
	});
})(window.jQuery || window.Zepto);
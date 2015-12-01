/**
 * @tableofcontents
 *
 * 1. call home
 * 2. init
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, _gaq)
{
	'use strict';

	/* @section 1. call home */

	$.fn.callHome = function (options)
	{
		/* extend options */

		if (rs.modules.callHome.options !== options)
		{
			options = $.extend({}, rs.modules.callHome.options, options || {});
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
				String(rs.version),
				String(rs.baseURL)
			]);
		}
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.callHome.init && rs.modules.callHome.dependency)
		{
			$.fn.callHome(rs.modules.callHome.options);
		}
	});
})(window.jQuery || window.Zepto, window._gaq);

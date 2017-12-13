/**
 * @tableofcontents
 *
 * 1. call home
 * 2. init
 */

(function ($, ga)
{
	'use strict';

	/** @section 1. call home */

	$.fn.callHome = function (options)
	{
		/* extend options */

		if (rs.modules.callHome.options !== options)
		{
			options = $.extend({}, rs.modules.callHome.options, options || {});
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
			ga('send',
			{
				hitType: 'event',
				eventCategory: 'call-home',
				eventAction: rs.version,
				eventLabel: rs.baseURL
			});
		}
	};

	/** @section 2. init */

	$(function ()
	{
		if (rs.modules.callHome.init && rs.modules.callHome.dependency)
		{
			$.fn.callHome(rs.modules.callHome.options);
		}
	});
})(window.jQuery, window.ga);

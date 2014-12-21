/**
 * @tableofcontents
 *
 * 1. web app
 * 2. startup
 *
 * @since 2.0.2
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. web app */

	$.fn.webApp = function (options)
	{
		/* extend options */

		if (rxs.modules.webApp.options !== options)
		{
			options = $.extend({}, rxs.modules.webApp.options, options || {});
		}

		/* trigger install */

		if (rxs.support.webStorage && typeof window.navigator.mozApps === 'object')
		{
			var counter  = Number(window.localStorage.getItem('webAppInstallCounter')) || 0,
				request = '';

			/* prevent multiple request */

			if (counter < options.limit)
			{
				request = window.navigator.mozApps.install(rxs.baseURL + 'manifest_webapp');

				/* count multiple request */

				window.localStorage.setItem('webAppInstallCounter', ++counter);

				/* handle success */

				request.onsuccess  = function ()
				{
					window.localStorage.setItem('webAppInstallCounter', options.limit);
				};
			}
		}
	};

	/* @section 2. startup */

	$(function ()
	{
		if (rxs.modules.webApp.startup)
		{
			$.fn.webApp(rxs.modules.webApp.options);
		}
	});
})(window.jQuery || window.Zepto);
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

		if (r.modules.webApp.options !== options)
		{
			options = $.extend({}, r.modules.webApp.options, options || {});
		}

		/* trigger installation */

		if (r.support.webStorage && typeof window.navigator.mozApps === 'object')
		{
			var counter  = Number(window.localStorage.getItem('webAppInstallCounter')) || 0,
				request = '';

			/* prevent multiple request */

			if (counter < options.limit)
			{
				request = window.navigator.mozApps.install(r.baseURL + 'manifest_webapp');

				/* count multiple request */

				window.sessionStorage.setItem('webAppInstallCounter', ++counter);

				/* handle success */

				request.onsuccess  = function ()
				{
					window.sessionStorage.setItem('webAppInstallCounter', options.limit);
				};
			}
		}
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.webApp.startup)
		{
			$.fn.webApp(r.modules.webApp.options);
		}
	});
})(window.jQuery || window.Zepto);
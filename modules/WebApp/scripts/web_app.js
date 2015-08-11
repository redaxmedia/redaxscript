/**
 * @tableofcontents
 *
 * 1. web app
 * 2. init
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

		if (rs.modules.webApp.options !== options)
		{
			options = $.extend({}, rs.modules.webApp.options, options || {});
		}

		/* trigger install */

		if (rs.support.webStorage && typeof navigator.mozApps === 'object')
		{
			var counter  = Number(localStorage.getItem('webAppInstallCounter')) || 0,
				request = '';

			/* prevent multiple request */

			if (counter < options.limit)
			{
				request = navigator.mozApps.install(rs.baseURL + 'manifest_webapp');

				/* count multiple request */

				localStorage.setItem('webAppInstallCounter', ++counter);

				/* handle success */

				request.onsuccess  = function ()
				{
					localStorage.setItem('webAppInstallCounter', options.limit);
				};
			}
		}
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.webApp.init)
		{
			$.fn.webApp(rs.modules.webApp.options);
		}
	});
})(window.jQuery || window.Zepto);

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

	$.fn.web_app = function (options)
	{
		/* extend options */

		if (r.modules.web_app.options !== options)
		{
			options = $.extend({}, r.modules.web_app.options, options || {});
		}

		/* install web app */

		if (r.support.webStorage && typeof window.navigator.mozApps === 'object')
		{
			var reminder = Number(window.sessionStorage.getItem('webAppReminder')) || 0;

			/* prevent multiple reminder */

			if (reminder < options.reminder)
			{
				window.sessionStorage.setItem('webAppReminder', ++reminder)
				window.navigator.mozApps.install(r.baseURL + 'manifest_webapp');
			}
		}
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.shareThis.startup)
		{
			$.fn.web_app(r.modules.web_app.options);
		}
	});
})(window.jQuery || window.Zepto);
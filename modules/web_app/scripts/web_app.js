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

	$.fn.web_app = function ()
	{
		if (typeof window.navigator.mozApps === 'object')
		{
			window.navigator.mozApps.install(r.baseURL + 'manifest_webapp');
		}
	};

	/* @section 2. startup */

	$(function ()
	{
		$.fn.web_app();
	});
})(window.jQuery || window.Zepto);
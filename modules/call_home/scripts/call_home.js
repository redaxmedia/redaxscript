/**
 * @tableofcontents
 *
 * 1. call home
 * 2. startup
 */

(function ($)
{
	'use strict';

	/* @section 1. call home */

	$.fn.callHome = function (options)
	{
		/* extend options */

		if (r.modules.callHome.options !== options)
		{
			options = $.extend({}, r.modules.callHome.options, options || {});
		}

		/* create tracker */

		if (options.id && options.url)
		{
			r.modules.callHome.tracker = _gat._createTracker(options.id);
			r.modules.callHome.tracker._setDomainName(options.url);
			r.modules.callHome.tracker._initData();
			r.modules.callHome.tracker._trackPageview();

			/* track event */

			r.modules.callHome.tracker._trackEvent('call-home', String(r.version), String(r.baseURL));
		}
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.callHome.startup && r.constants.LOGGED_IN === r.constants.TOKEN && r.constants.FIRST_PARAMETER === 'admin' && r.constants.ADMIN_PARAMETER === '' && typeof _gat === 'object')
		{
			$.fn.callHome(r.modules.callHome.options);
		}
	});
})(window.jQuery || window.Zepto);
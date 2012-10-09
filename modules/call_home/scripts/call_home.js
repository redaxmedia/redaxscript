(function ($)
{
	/* call home */

	$.fn.callHome = function (options)
	{
		/* extend options */

		if (r.module.callHome.options !== options)
		{
			options = $.extend({}, r.module.callHome.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* create tracker */

			if (options.id && options.url)
			{
				r.module.callHome.tracker = _gat._createTracker(options.id);
				r.module.callHome.tracker._setDomainName(options.url);
				r.module.callHome.tracker._initData();
				r.module.callHome.tracker._trackPageview();

				/* call event */

				r.module.callHome.tracker._trackEvent('call-home', r.version, r.baseURL);
			}
		});
	};
})(jQuery);

jQuery(function ($)
{
	/* startup */

	if (r.module.callHome.startup && r.constant.LOGGED_IN === r.constant.TOKEN && r.constant.FIRST_PARAMETER === 'admin' && r.constant.ADMIN_PARAMETER === '' && typeof _gat === 'object')
	{
		$.fn.callHome(r.module.callHome.options);
	}
});
(function ($)
{
	/* qunit */

	$.fn.qunit = function (options)
	{
		/* extend options */

		if (r.module.qunit.options !== options)
		{
			options = $.extend({}, r.module.qunit.options, options || {});
		}

		var win = window;

		/* done callback */

		win.QUnit.done = function ()
		{
			var qunit = $(options.element.qunit),
				qunitHeader = qunit.find(options.element.qunitHeader),
				qunitBanner = qunit.find(options.element.qunitBanner),
				qunitToolbar = qunit.find(options.element.qunitToolbar),
				qunitUserAgent = qunit.find(options.element.qunitUserAgent),
				qunitResult = qunit.find(options.element.qunitResult),
				qunitTest = qunit.find(options.element.qunitTest),
				qunitFixture = qunit.find(options.element.qunitFixture);

			/* add several classes */

			qunitHeader.addClass(options.classString.qunitHeader);
			qunitBanner.addClass(options.classString.qunitBanner);
			qunitToolbar.addClass(options.classString.qunitToolbar);
			qunitUserAgent.addClass(options.classString.qunitUserAgent);
			qunitResult.addClass(options.classString.qunitResult);
			qunitTest.addClass(options.classString.qunitTest);
		};
	};
})(jQuery);

jQuery(function ($)
{
	/* startup */

	if (r.constant.FIRST_PARAMETER === 'qunit')
	{
		$(r.module.qunit.selector).qunit(r.module.qunit.options);
	}
});
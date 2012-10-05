(function ($)
{
	/* editor */

	$.fn.qunit = function (options)
	{
		/* extend options */

		if (r.module.qunit.options !== options)
		{
			options = $.extend({}, r.module.qunit.options, options || {});
		}

		var win = window,
			qunit = $(this);

		/* done callback */

		win.QUnit.done = function ()
		{
			var qunitHeader = qunit.find(options.element.qunitHeader),
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

		/* test redaxscript */

		win.test('global', function()
		{
			var expect = 'object',
				result = typeof r && typeof l;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* test base url */

		win.test('baseURL', function()
		{
			var expect = 'string',
				result = typeof r.baseURL;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);

		});

		/* test clean alias */

		if (typeof $.fn.cleanAlias === 'function')
		{
			win.test('cleanAlias', function()
			{
				var expect = 'hello-world',
					result = $.fn.cleanAlias('Hello world');

				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
			});
		}
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
jQuery(function ($)
{
	var win = window;

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
});
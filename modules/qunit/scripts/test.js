/**
 * @tableofcontents
 *
 * 1. test
 */

(function ($)
{
	'use strict';

	/* @section 1. test */

	$(function ()
	{
		var win = window,
			fixture = $(r.module.qunit.options.element.qunitFixture);

		/* globals */

		win.test('globals', function ()
		{
			var expect = 'object',
				result = typeof r && typeof l;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* base url */

		win.test('baseURL', function ()
		{
			var expect = 'string',
				result = typeof r.baseURL;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* version */

		win.test('version', function ()
		{
			var expect = 'number',
				result = typeof r.version;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* clean alias */

		if (typeof $.fn.cleanAlias === 'function')
		{
			win.test('cleanAlias', function ()
			{
				var expect = 'hello-world',
					result = $.fn.cleanAlias('Hello world');

				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* clear focus */

		if (typeof $.fn.clearFocus === 'function')
		{
			win.test('clearFocus', function ()
			{
				var input = $('<input value="Hello world" />').clearFocus().appendTo(fixture),
					expect = '',
					result = input.val();

				/* trigger focusin */

				result = input.trigger('focusin').val();
				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);

				/* trigger focusout */

				expect = 'Hello world';
				result = input.trigger('focusout').val();
				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
			});
		}
	});
})(jQuery);
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
			fixture = $(r.module.qunit.options.element.qunitFixture),
			dummy = 'Hello world';

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

		/* constant */

		win.test('constant', function ()
		{
			var expect = 'object',
				result = typeof r.constant;

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
					result = $.fn.cleanAlias(dummy);

				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* check search */

		if (typeof $.fn.checkSearch === 'function')
		{
			win.asyncTest('checkSearch', function ()
			{
				var form = $('<form method="post"><input value="' + dummy + '" class="js_required" /></form>').checkSearch().appendTo(fixture),
					input = form.find('input'),
					expect = l.input_incorrect + l.exclamation_mark,
					result = input.val();

				/* trigger submit */

				form.submit();
				setTimeout(function ()
				{
					result = input.val();
					win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
					win.start();
				}, 100);
			});
		}

		/* clear focus */

		if (typeof $.fn.clearFocus === 'function')
		{
			win.test('clearFocus', function ()
			{
				var input = $('<input value="' + dummy + '" />').clearFocus().appendTo(fixture),
					expect = '',
					result = input.val();

				/* trigger focusin */

				result = input.trigger('focusin').val();
				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);

				/* trigger focusout */

				expect = dummy;
				result = input.trigger('focusout').val();
				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* unmask password  */

		if (typeof $.fn.unmaskPassword === 'function')
		{
			win.test('unmaskPassword', function ()
			{
				var input = $('<input type="password" value="' + dummy + '" />').unmaskPassword().appendTo(fixture),
					expect = 'text',
					result = input.attr('type'),
					keydown = $.Event('keydown');

				keydown.ctrlKey = true;
				keydown.altKey = true;

				/* trigger keydown */

				result = input.trigger(keydown).attr('type');
				win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);

				/* trigger focusout */

				expect = 'password';
				result = input.trigger('focusout').attr('type');
				win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
			});
		}
	});
})(jQuery);
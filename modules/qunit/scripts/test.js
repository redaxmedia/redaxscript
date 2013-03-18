/**
 * @tableofcontents
 *
 * 1. jquery
 * 2. globals
 * 3. base url
 * 4. constant
 * 5. version
 * 6. clean alias
 * 7. auto resize
 * 8. check search
 * 9. clear focus
 * 10. unmask password
 */

(function ($)
{
	'use strict';

	$(function ()
	{
		var win = window,
			fixture = $(r.module.qunit.options.element.qunitFixture),
			dummy = 'Hello world';

		/* @section 1. jquery */

		win.test('jquery', function ()
		{
			var expect = 'function',
				result = typeof jQuery;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 2. globals */

		win.test('globals', function ()
		{
			var expect = 'object',
				result = typeof r && typeof l;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 3. base url */

		win.test('baseURL', function ()
		{
			var expect = 'string',
				result = typeof r.baseURL;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 4. constant */

		win.test('constant', function ()
		{
			var expect = 'object',
				result = typeof r.constant;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 5. support */

		win.test('support', function ()
		{
			var expect = 'object',
				result = typeof r.support;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 6. version */

		win.test('version', function ()
		{
			var expect = 'number',
				result = typeof r.version;

			win.equal(result, expect, l.qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 7. clean alias */

		if (typeof $.fn.cleanAlias === 'function')
		{
			win.test('cleanAlias', function ()
			{
				var expect = 'hello-world',
					result = $.fn.cleanAlias(dummy);

				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* @section 8. auto resize */

		if (typeof $.fn.autoResize === 'function')
		{
			win.test('autoResize', function ()
			{
				var textarea = $('<textarea cols="1" rows="2"></textarea>').autoResize().appendTo(fixture),
					expect = 1,
					result = textarea.attr('rows');

				/* trigger ready */

				result = textarea.trigger('ready').attr('rows');
				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);

				/* trigger input */

				expect = dummy.length;
				result = textarea.val(dummy).trigger('input').attr('rows');
				win.equal(result, expect, l.qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* @section 9. check search */

		if (typeof $.fn.checkSearch === 'function')
		{
			win.asyncTest('checkSearch', function ()
			{
				var form = $('<form method="post"><input class="js_required" value="' + dummy + '" /></form>').checkSearch().appendTo(fixture),
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

		/* @section 10. clear focus */

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

		/* @section 11. unmask password  */

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
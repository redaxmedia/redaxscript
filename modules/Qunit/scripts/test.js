/**
 * @tableofcontents
 *
 * 1. library
 * 2. globals
 * 3. base url
 * 4. constants
 * 5. support
 * 6. version
 * 7. clean alias
 * 8. fetch keyword
 * 9. auto resize
 * 10. validate search
 * 11. enable indent
 * 12. unmask password
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	$(function ()
	{
		var win = window,
			fixture = $(r.modules.qunit.options.element.qunitFixture),
			dummy = 'hello world';

		/* @section 1. library */

		win.test('library', function ()
		{
			var expect = 'function',
				result = typeof $;

			win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 2. globals */

		win.test('globals', function ()
		{
			var expect = 'object',
				result = typeof r && typeof l;

			win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 3. base url */

		win.test('baseURL', function ()
		{
			var expect = 'string',
				result = typeof r.baseURL;

			win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 4. constants */

		win.test('constants', function ()
		{
			var expect = 'number',
				result = typeof Object.keys(r.constants).length;

			win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 5. support */

		win.test('support', function ()
		{
			var expect = 'number',
				result = typeof Object.keys(r.support).length;

			win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 6. version */

		win.test('version', function ()
		{
			var expect = 'string',
				result = typeof r.version;

			win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);
		});

		/* @section 7. clean alias */

		if (typeof $.fn.cleanAlias === 'function')
		{
			win.test('cleanAlias', function ()
			{
				var expect = 'hello-world',
					result = $.fn.cleanAlias(dummy);

				win.equal(result, expect, l._qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* @section 8. fetch keyword */

		if (typeof $.fn.fetchKeyword === 'function')
		{
			win.test('generateKeyword', function ()
			{
				var expect = 'hello world',
					result = $.fn.fetchKeyword('<span>lorim <strong> hello world </strong> impsum', {
						element:
						{
							target: 'h1, h2, h3, strong'
						},
						delimiter: ' ',
						limit: 10
					});

				win.equal(result, expect, l._qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* @section 9. auto resize */

		if (typeof $.fn.autoResize === 'function')
		{
			win.test('autoResize', function ()
			{
				var textarea = $('<textarea cols="5" rows="5"></textarea>').autoResize().appendTo(fixture),
					expect = 1,
					result = textarea.attr('rows');

				/* trigger focus */

				result = textarea.trigger('focus').attr('rows');
				win.equal(result, expect, l._qunit_value_expected + l.colon + ' ' + expect);

				/* trigger input */

				result = textarea.val(dummy).trigger('input').attr('rows');
				win.notEqual(result, expect, l._qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* @section 10. validate search */

		if (typeof $.fn.validateSearch === 'function' && r.support.input.placeholder)
		{
			win.test('validateSearch', function ()
			{
				var form = $('<form><input class="js_search" placeholder="' + dummy + '" /></form>').validateSearch().appendTo(fixture),
					input = form.children('input'),
					expect = l.input_incorrect + l.exclamation_mark,
					result = input.attr('placeholder');

				/* trigger submit */

				form.submit();
				result = input.attr('placeholder');
				win.equal(result, expect, l._qunit_attribute_expected + l.colon + ' ' + expect);
			});
		}

		/* @section 11. enable indent */

		if (typeof $.fn.enableIndent === 'function')
		{
			win.test('enableIndent', function ()
			{
				var textarea = $('<textarea cols="5" rows="5"></textarea>').enableIndent().appendTo(fixture),
					expect = r.plugins.enableIndent.options.indent,
					result = textarea.val(),
					keydown = $.Event('keydown');

				keydown.which  = 9;

				/* trigger keydown */

				result = textarea.trigger(keydown).val();
				win.equal(result, expect, l._qunit_value_expected + l.colon + ' ' + expect);
			});
		}

		/* @section 12. unmask password  */

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
				win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);

				/* trigger blur */

				expect = 'password';
				result = input.trigger('blur').attr('type');
				win.equal(result, expect, l._qunit_type_expected + l.colon + ' ' + expect);
			});
		}
	});
})(window.jQuery || window.Zepto);
/**
 * @tableofcontents
 *
 * 1. framework
 * 2. namespace
 * 3. base url
 * 4. registry
 * 5. support
 * 6. version
 * 7. clean alias
 * 8. g keyword
 * 9. auto resize
 * 10. validate search
 * 11. enable indent
 * 12. unmask password
 *
 * @since 2.6.0
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
			fixture = $(rs.modules.qunit.options.element.qunitFixture),
			dummy = 'hello world';

		/* @section 1. framework */

		win.test('framework', function ()
		{
			var expect = 'function',
				actual = typeof $;

			win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);
		});

		/* @section 2. namespace */

		win.test('namespace', function ()
		{
			var expect = 'object',
				actual = typeof rs;

			win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);
		});

		/* @section 3. base url */

		win.test('baseURL', function ()
		{
			var expect = 'string',
				actual = typeof rs.baseURL;

			win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);
		});

		/* @section 4. registry */

		win.test('registry', function ()
		{
			var expect = 'number',
				actual = typeof Object.keys(rs.registry).length;

			win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);
		});

		/* @section 5. support */

		win.test('support', function ()
		{
			var expect = 'number',
				actual = typeof Object.keys(rs.support).length;

			win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);
		});

		/* @section 6. version */

		win.test('version', function ()
		{
			var expect = 'string',
				actual = typeof rs.version;

			win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);
		});

		/* @section 7. clean alias */

		if (typeof $.fn.cleanAlias === 'function')
		{
			win.test('cleanAlias', function ()
			{
				var expect = 'hello-world',
					actual = $.fn.cleanAlias(dummy);

				win.equal(actual, expect, rs.language._qunit.value_expected + rs.language.colon + ' ' + expect);
			});
		}

		/* @section 8. g keyword */

		if (typeof $.fn.fetchKeyword === 'function')
		{
			win.test('fetchKeyword', function ()
			{
				var expect = 'hello world',
					actual = $.fn.fetchKeyword('<span>lorim <strong> hello world </strong> impsum',
					{
						element:
						{
							target: 'h1, h2, h3, strong'
						},
						delimiter: ' ',
						limit: 10
					});

				win.equal(actual, expect, rs.language._qunit.value_expected + rs.language.colon + ' ' + expect);
			});
		}

		/* @section 9. auto resize */

		if (typeof $.fn.autoResize === 'function')
		{
			win.test('autoResize', function ()
			{
				var textarea = $('<textarea cols="5" rows="5"></textarea>').autoResize().appendTo(fixture),
					expect = 1,
					actual = textarea.attr('rows');

				/* trigger focus */

				actual = textarea.trigger('focus').attr('rows');
				win.equal(actual, expect, rs.language._qunit.value_expected + rs.language.colon + ' ' + expect);

				/* trigger input */

				actual = textarea.val(dummy).trigger('input').attr('rows');
				win.notEqual(actual, expect, rs.language._qunit.value_expected + rs.language.colon + ' ' + expect);
			});
		}

		/* @section 10. validate search */

		if (typeof $.fn.validateSearch === 'function' && rs.support.input.placeholder)
		{
			win.test('validateSearch', function ()
			{
				var form = $('<form><input class="rs-js-search" placeholder="' + dummy + '" /></form>').validateSearch().appendTo(fixture),
					input = form.children('input'),
					expect = rs.language.input_incorrect + rs.language.exclamation_mark,
					actual = input.attr('placeholder');

				/* trigger submit */

				form.submit();
				actual = input.attr('placeholder');
				win.equal(actual, expect, rs.language._qunit.attribute_expected + rs.language.colon + ' ' + expect);
			});
		}

		/* @section 11. enable indent */

		if (typeof $.fn.enableIndent === 'function')
		{
			win.test('enableIndent', function ()
			{
				var textarea = $('<textarea cols="5" rows="5"></textarea>').enableIndent().appendTo(fixture),
					expect = rs.plugins.enableIndent.options.indent,
					actual = textarea.val(),
					keydown = $.Event('keydown');

				keydown.which  = 9;

				/* trigger keydown */

				actual = textarea.trigger(keydown).val();
				win.equal(actual, expect, rs.language._qunit.value_expected + rs.language.colon + ' ' + expect);
			});
		}

		/* @section 12. unmask password  */

		if (typeof $.fn.unmaskPassword === 'function')
		{
			win.test('unmaskPassword', function ()
			{
				var input = $('<input type="password" value="' + dummy + '" />').unmaskPassword().appendTo(fixture),
					expect = 'text',
					actual = input.attr('type'),
					keydown = $.Event('keydown');

				keydown.ctrlKey = true;
				keydown.altKey = true;

				/* trigger keydown */

				actual = input.trigger(keydown).attr('type');
				win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);

				/* trigger blur */

				expect = 'password';
				actual = input.trigger('blur').attr('type');
				win.equal(actual, expect, rs.language._qunit.type_expected + rs.language.colon + ' ' + expect);
			});
		}
	});
})(window.jQuery || window.Zepto);
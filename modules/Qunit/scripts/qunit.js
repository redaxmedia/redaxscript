/**
 * @tableofcontents
 *
 * 1. qunit
 * 2. init
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. qunit */

	$.fn.qunit = function (options)
	{
		/* extend options */

		if (rs.modules.qunit.options !== options)
		{
			options = $.extend({}, rs.modules.qunit.options, options || {});
		}

		var win = window,
			qunit = $(options.element.qunit),
			qunitFixture = qunit.next(options.element.qunitFixture);

		/* add classes */

		qunitFixture.addClass(options.className.qunitFixture);

		/* begin callback */

		win.QUnit.begin = function ()
		{
			qunit.hide();
		};

		/* done callback */

		win.QUnit.done = function ()
		{
			var qunitHeader = qunit.find(options.element.qunitHeader),
				qunitBanner = qunit.find(options.element.qunitBanner),
				qunitToolbar = qunit.find(options.element.qunitToolbar),
				qunitUserAgent = qunit.find(options.element.qunitUserAgent),
				qunitResult = qunit.find(options.element.qunitResult),
				qunitTest = qunit.find(options.element.qunitTest),
				qunitAssert = qunitTest.find(options.element.qunitAssert);

			/* add several classes */

			qunitHeader.addClass(options.className.qunitHeader);
			qunitBanner.addClass(options.className.qunitBanner);
			qunitToolbar.addClass(options.className.qunitToolbar);
			qunitUserAgent.addClass(options.className.qunitUserAgent);
			qunitResult.addClass(options.className.qunitResult);
			qunitTest.addClass(options.className.qunitTest);
			qunitAssert.addClass(options.className.qunitAssert);

			/* detach and extend banner */

			if (qunitBanner.hasClass('qunit-pass'))
			{
				qunitBanner.addClass('note_success').text(rs.language._qunit.test_passed + rs.language.point);
			}
			else if (qunitBanner.hasClass('qunit-fail'))
			{
				qunitBanner.addClass('note_error').text(rs.language._qunit.test_failed + rs.language.point);
			}
			qunitBanner.detach().insertAfter(qunitUserAgent);

			/* replace break in result */

			qunitResult.find('br').replaceWith(' ');

			/* show qunit */

			qunit.show();
		};
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.qunit.init)
		{
			$.fn.qunit(rs.modules.qunit.options);
		}
	});
})(window.jQuery || window.Zepto);

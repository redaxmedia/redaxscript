/**
 * @tableofcontents
 *
 * 1. qunit
 * 2. startup
 */

(function ($)
{
	'use strict';

	/* @section 1. qunit */

	$.fn.qunit = function (options)
	{
		/* extend options */

		if (r.modules.qunit.options !== options)
		{
			options = $.extend({}, r.modules.qunit.options, options || {});
		}

		var win = window,
			qunit = $(options.element.qunit),
			qunitFixture = qunit.next(options.element.qunitFixture);

		/* add classes */

		qunitFixture.addClass(options.classString.qunitFixture);

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
				qunitTest = qunit.find(options.element.qunitTest);

			/* add several classes */

			qunitHeader.addClass(options.classString.qunitHeader);
			qunitBanner.addClass(options.classString.qunitBanner);
			qunitToolbar.addClass(options.classString.qunitToolbar);
			qunitUserAgent.addClass(options.classString.qunitUserAgent);
			qunitResult.addClass(options.classString.qunitResult);
			qunitTest.addClass(options.classString.qunitTest);

			/* detach and extend banner */

			if (qunitBanner.hasClass('qunit-pass'))
			{
				qunitBanner.addClass('note_success').text(l.qunit_test_passed + l.point);
			}
			else if (qunitBanner.hasClass('qunit-fail'))
			{
				qunitBanner.addClass('note_error').text(l.qunit_test_failed + l.point);
			}
			qunitBanner.detach().insertAfter(qunitUserAgent);

			/* replace break in result */

			qunitResult.find('br').replaceWith(' ');

			/* fade in qunit */

			qunit.fadeIn(options.duration);
		};
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.constants.FIRST_PARAMETER === 'qunit')
		{
			$.fn.qunit(r.modules.qunit.options);
		}
	});
})(window.jQuery || window.Zepto);
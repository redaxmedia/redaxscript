/**
 * @tableofcontents
 *
 * 1. fetch keyword
 * 2. generate keyword
 * 3. startup
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. fetch keyword */

	$.fn.fetchKeyword = function (field, options)
	{
		var text = field.text(),
			textNode = $(text),
			outputArray = [],
			output = '';

		/* proccess text node */

		textNode.each(function ()
		{
			var that = $(this),
				thatNative = that[0],
				thatTag = thatNative.tagName;

			/* validate node tag */

			if (thatTag)
			{
				thatTag = thatTag.toLowerCase();
				if ($.inArray(thatTag, options.tag) > -1 && outputArray.length < options.limit)
				{
					$.merge(outputArray, that.text().toLowerCase().split(' '));
				}
			}
		});
		output = outputArray.join(options.delimiter);
		return output;
	};

	/* @section 2. generate keyword */

	$.fn.generateKeyword = function (options)
	{
		/* extend options */

		if (r.plugins.generateKeyword.options !== options)
		{
			options = $.extend({}, r.plugins.generateKeyword.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			/* listen for change and input */

			$(this).on('change input', function ()
			{
				var field = $(this),
					form = field.closest('form'),
					related = form.find(options.element.related),
					fieldText = $.trim(field.text()),
					keywordValue = '';

				/* fetch keyword from text */

				if (fieldText)
				{
					keywordValue = $.fn.fetchKeyword(field, options);
					if (keywordValue)
					{
						related.val(keywordValue);
					}
				}

				/* else clear related value */

				else
				{
					related.val('');
				}
			});
		});
	};

	/* @section 3. startup */

	$(function ()
	{
		if (r.plugins.generateKeyword.startup)
		{
			$(r.plugins.generateKeyword.selector).generateKeyword(r.plugins.generateKeyword.options);
		}
	});
})(window.jQuery || window.Zepto);
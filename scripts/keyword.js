/**
 * @tableofcontents
 *
 * 1. fetch keyword
 * 2. generate keyword
 * 3. init
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

	$.fn.fetchKeyword = function (input, options)
	{
		var node = $('<div>').append(input),
			target = node.find(options.element.target).slice(0, options.limit),
			keywordArray = [],
			output = '';

		/* proccess target elements */

		target.each(function ()
		{
			var that = $(this),
				text = $.trim(that.text().toLowerCase());

			$.merge(keywordArray, text.split(' '));
		});

		/* join array */

		if (keywordArray.length)
		{
			output = keywordArray.slice(0, options.limit).join(options.delimiter);
		}
		return output;
	};

	/* @section 2. generate keyword */

	$.fn.generateKeyword = function (options)
	{
		/* extend options */

		if (rs.plugins.generateKeyword.options !== options)
		{
			options = $.extend({}, rs.plugins.generateKeyword.options, options || {});
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
					fieldValue = $.trim(field.val()),
					keywordValue = '';

				/* fetch keyword from value */

				if (fieldValue)
				{
					keywordValue = $.fn.fetchKeyword(fieldValue, options);
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

	/* @section 3. init */

	$(function ()
	{
		if (rs.plugins.generateKeyword.init)
		{
			$(rs.plugins.generateKeyword.selector).generateKeyword(rs.plugins.generateKeyword.options);
		}
	});
})(window.jQuery || window.Zepto);
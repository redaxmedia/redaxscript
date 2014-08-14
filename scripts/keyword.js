/**
 * @tableofcontents
 *
 * 1. generate keyword
 * 2. startup
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. generate keyword */

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
					fiedValue = $.trim(field.val()),
					related = form.find(options.element.field),
					keywordValue = '';

			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.plugins.generateKeyword.startup)
		{
			$(r.plugins.generateKeyword.selector).generateKeyword(r.plugins.generateKeyword.options);
		}
	});
})(window.jQuery || window.Zepto);

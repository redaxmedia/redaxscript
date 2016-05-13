/**
 * @tableofcontents
 *
 * 1. ckeditor
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, ckeditor)
{
	'use strict';

	/* @section 1. ckeditor */

	$.fn.ckeditor = function (options)
	{
		/* extend options */

		if (rs.modules.ckeditor.options !== options)
		{
			options = $.extend({}, rs.modules.ckeditor.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var textarea = $(this),
				name = textarea.attr('name');

			ckeditor.replace(name, options);
		});
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.ckeditor.init && rs.modules.ckeditor.dependency)
		{
			$(rs.modules.ckeditor.selector).ckeditor(rs.modules.ckeditor.options);
		}
	});
})(window.jQuery || window.Zepto, window.CKEDITOR);

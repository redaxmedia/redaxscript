/**
 * @tableofcontents
 *
 * 1. tinymce
 * 2. init
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($, tinymce)
{
	'use strict';

	/* @section 1. tinymce */

	$.fn.tinymce = function (options)
	{
		/* extend options */

		if (rs.modules.tinymce.options !== options)
		{
			options = $.extend({}, rs.modules.tinymce.options, options || {});
		}

		/* upload on change */

		options.setup = options.setup || function (editor)
		{
			editor.on('change', function ()
			{
				tinymce.activeEditor.uploadImages();
			});
		};
		tinymce.init(options);
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.tinymce.init && rs.modules.tinymce.dependency)
		{
			$.fn.tinymce(rs.modules.tinymce.options);
		}
	});
})(window.jQuery || window.Zepto, window.tinymce);

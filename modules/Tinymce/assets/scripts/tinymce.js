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

		var tagArray = options.custom_elements.split(', '),
			i;

		/* extend setup */

		options.setup = options.setup || function (editor)
		{
			/* upload on change */

			editor.on('change', function ()
			{
				tinymce.activeEditor.uploadImages();
				tinymce.triggerSave();
			});

			/* pseudo tags */

			for (i in tagArray)
			{
				(function (tag)
				{
					editor.addMenuItem(tag,
					{
						text: tag,
						context: 'insert',
						onclick: function ()
						{
							editor.insertContent('<' + tag + '>');
						}
					});
				})(tagArray[i]);
			}
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

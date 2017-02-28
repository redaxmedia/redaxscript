/**
 * @tableofcontents
 *
 * 1. tinymce
 *    1.1 file picker
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

			/* content tags */

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
							editor.dom.add(editor.getBody(), tag);
						}
					});
				})(tagArray[i]);
			}
		};

		/* file picker */

		if (options.file_picker_callback)
		{
			options.file_picker_callback = function (callback)
			{
				$.fn.tinymceFilePicker(callback);
			};
		}
		tinymce.init(options);
	};

	/* @section 1.1 file picker */

	$.fn.tinymceFilePicker = function(callback)
	{
		var field = $('<input>');

		field
			.attr(
			{
				type: 'file',
				accept: 'image/jpg,image/png,image/svg'
			})
			.on('change', function ()
			{
				var that = $(this),
					file = that.get(0).files[0],
					blobCache = tinymce.activeEditor.editorUpload.blobCache,
					blobInfo = blobCache.create(Date.now(), file);

				blobCache.add(blobInfo);
				callback(blobInfo.blobUri(),
				{
					title: file.name
				});
			})
			.trigger('click');
	};

	/* @section 2. init */

	$(function ()
	{
		if (rs.modules.tinymce.init && rs.modules.tinymce.dependency)
		{
			$.fn.tinymce(rs.modules.tinymce.options);
		}
	});
})(window.jQuery, window.tinymce);
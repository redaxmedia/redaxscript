/**
 * @tableofcontents
 *
 * 1. file manager
 * 2. startup
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

(function ($)
{
	'use strict';

	/* @section 1. file manager */

	$.fn.fileManager = function (options)
	{
		/* extend options */

		if (r.modules.fileManager.options !== options)
		{
			options = $.extend({}, r.modules.fileManager.options, options || {});
		}

		/* return this */

		return this.each(function ()
		{
			var form = $(this),
				fieldFile = form.find(options.element.fieldFile),
				buttonUpload = form.find(options.element.buttonUpload),
				buttonBrowse = $('<button type="submit">' + l.file_manager.browse + '</button>').addClass(options.className.buttonBrowse);

			/* insert fake browse */

			buttonBrowse.insertBefore(buttonUpload)

			/* listen for click */

			.on('click', function (event)
			{
				fieldFile.click();
				buttonUpload.hide();
				event.preventDefault();
			});

			/* show upload on change */

			fieldFile.change(function ()
			{
				buttonUpload.show();
			});
		});
	};

	/* @section 2. startup */

	$(function ()
	{
		if (r.modules.fileManager.startup && r.constants.ADMIN_PARAMETER === 'file-manager')
		{
			$(r.modules.fileManager.selector).fileManager(r.modules.fileManager.options);
		}
	});
})(window.jQuery || window.Zepto);
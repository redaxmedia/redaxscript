/**
 * @tableofcontents
 *
 * 1. file manager
 * 2. startup
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
				buttonBrowse;

			/* insert fake browse */

			buttonBrowse = $('<button type="submit" class="js_browse field_button_admin"><span><span>' + l.file_manager_browse + '</span></span></button').insertBefore(buttonUpload);

			/* listen for click */

			buttonBrowse.on('click', function (event)
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
})(jQuery);
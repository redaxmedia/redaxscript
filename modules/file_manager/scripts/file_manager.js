(function ($)
{
	/* file manager */

	$.fn.fileManager = function (options)
	{
		/* extend options */

		if (r.module.fileManager.options !== options)
		{
			options = $.extend({}, r.module.fileManager.options, options || {});
		}

		var form = $(this),
			fieldFile = form.find(options.element.fieldFile),
			buttonUpload = form.find(options.element.buttonUpload),
			buttonBrowse;

		/* insert fake browse */

		buttonBrowse = $('<button type="submit" class="js_browse field_button_admin"><span><span>' + l.file_manager_browse + '</span></span></button').insertBefore(buttonUpload);

		/* browse drive on click */

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
	};
})(jQuery);

jQuery(function ($)
{
	/* startup */

	if (r.module.fileManager.startup && r.constant.ADMIN_PARAMETER === 'file-manager')
	{
		$(r.module.fileManager.selector).fileManager(r.module.fileManager.options);
	}
});
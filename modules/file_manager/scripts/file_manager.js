(function ($)
{
	/* file manager */

	$.fn.fileManager = function ()
	{
		/* define variables */

		var form = $(this),
			fieldFile = form.find('input.js_file'),
			fieldUpload = form.find('button.js_upload'),
			fieldBrowse;

		/* append fake browse */

		fieldBrowse = $('<button type="submit" class="js_browse field_button_admin"><span><span>' + l.file_manager_browse + '</span></span></button').prependTo(form);

		/* browse drive on click */

		fieldBrowse.click(function ()
		{
			fieldFile.click();
			fieldUpload.hide();
			return false;
		});

		/* show upload on change */

		fieldFile.change(function ()
		{
			fieldUpload.show();
		});
	};
})(jQuery);

$(function ()
{
	/* startup */

	if (r.constant.ADMIN_PARAMETER == 'file-manager')
	{
		$('form.js_form_file_manager').fileManager();
	}
});
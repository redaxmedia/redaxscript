/**
 * @tableofcontents
 *
 * 1. file manager
 */

/* @section 1. file manager */

r.module.fileManager =
{
	startup: true,
	selector: 'form.js_form_file_manager',
	options:
	{
		element:
		{
			fieldFile: 'input.js_file',
			buttonUpload: 'button.js_upload'
		}
	}
};
/**
 * @tableofcontents
 *
 * 1. file manager
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. file manager */

rs.modules.fileManager =
{
	init: rs.registry.adminParameter === 'file-manager',
	selector: 'form.rs-js-form-file-manager',
	options:
	{
		element:
		{
			fieldFile: 'input.rs-js-file',
			buttonUpload: 'button.rs-js-upload'
		},
		className:
		{
			buttonBrowse: 'rs-js-browse rs-admin-button'
		}
	}
};

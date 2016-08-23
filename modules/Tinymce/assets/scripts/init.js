/**
 * @tableofcontents
 *
 * 1. tinymce
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. tinymce */

rs.modules.tinymce =
{
	init: rs.registry.lastTable === 'articles' || rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof tinymce === 'object',
	options:
	{
		selector: 'form textarea.rs-admin-js-editor-textarea'
	}
};

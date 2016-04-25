/**
 * @tableofcontents
 *
 * 1. ckeditor
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. ckeditor */

rs.modules.ckeditor =
{
	init: rs.registry.lastTable === 'articles' || rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof CKEDITOR === 'object',
	selector: 'form textarea.rs-admin-js-editor-textarea'
};

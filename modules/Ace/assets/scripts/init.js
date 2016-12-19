/**
 * @tableofcontents
 *
 * 1. ace
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. ace */

rs.modules.ace =
{
	init: rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof ace === 'object',
	selector: 'form textarea.rs-admin-js-editor-textarea',
	options:
	{
		mode: 'ace/mode/html'
	}
};

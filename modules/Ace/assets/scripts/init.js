/**
 * @tableofcontents
 *
 * 1. ace
 */

/** @section 1. ace */

rs.modules.ace =
{
	init: rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof ace === 'object',
	selector: 'form textarea.rs-admin-js-editor-textarea',
	options:
	{
		ace:
		{
			mode: 'ace/mode/html',
			maxLines: Infinity
		}
	}
};

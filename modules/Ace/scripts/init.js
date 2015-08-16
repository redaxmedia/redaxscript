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
	selector: '#text',
	options:
	{
		className:
		{
			hasAceEditor: 'has_ace_editor'
		},
		mode: 'ace/mode/html',
		theme: 'ace/theme/github'
	}
};
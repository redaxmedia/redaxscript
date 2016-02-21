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
	selector: 'form textarea.rs-js-editor-textarea',
	options:
	{
		className:
		{
			hasAceEditor: 'rs-has-ace-editor',
			aceEditor: 'rs-ace-editor'
		},
		mode: 'ace/mode/html',
		theme: 'ace/theme/github'
	}
};

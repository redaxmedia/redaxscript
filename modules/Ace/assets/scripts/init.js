rs.modules.Ace =
{
	init: rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof window.ace === 'object',
	config:
	{
		selector: 'form textarea.rs-admin-js-editor-textarea',
		mode: 'ace/mode/html',
		maxLines: Infinity
	}
};

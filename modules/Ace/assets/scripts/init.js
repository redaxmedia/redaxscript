rs.modules.Ace =
{
	init: rs.registry.loggedIn === rs.registry.token,
	dependency: typeof window.ace === 'object',
	optionArray:
	{
		selector: 'textarea.rs-admin-js-editor',
		ace:
		{
			showInvisibles: true,
			showGutter: false,
			useSoftTabs: false,
			maxLines: Infinity,
			mode: 'ace/mode/html'
		}
	}
};

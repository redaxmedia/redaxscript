rs.modules.TextareaResizer =
{
	frontend:
	{
		init: true,
		dependency: typeof window.autosize === 'function',
		optionArray:
		{
			selector: 'textarea.rs-js-resize'
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token,
		dependency: typeof window.autosize === 'function',
		optionArray:
		{
			selector: 'textarea.rs-admin-js-resize'
		}
	}
};

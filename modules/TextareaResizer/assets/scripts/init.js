rs.modules.TextareaResizer =
{
	frontend:
	{
		init: !rs.registry.adminParameter,
		dependency: typeof window.autosize === 'function',
		optionArray:
		{
			selector: 'textarea.rs-js-resize'
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
		dependency: typeof window.autosize === 'function',
		optionArray:
		{
			selector: 'textarea.rs-admin-js-resize'
		}
	}
};

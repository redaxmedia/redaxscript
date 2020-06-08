rs.modules.UnmaskPassword =
{
	frontend:
	{
		init: !rs.registry.adminParameter,
		optionArray:
		{
			selector: 'input.rs-js-password'
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
		optionArray:
		{
			selector: 'input.rs-admin-js-password'
		}
	}
};

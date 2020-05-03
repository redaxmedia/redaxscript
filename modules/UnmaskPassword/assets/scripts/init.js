rs.modules.UnmaskPassword =
{
	frontend:
	{
		init: true,
		optionArray:
		{
			selector: 'input.rs-js-password'
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token,
		optionArray:
		{
			selector: 'input.rs-admin-js-password'
		}
	}
};

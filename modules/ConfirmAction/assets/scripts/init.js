rs.modules.ConfirmAction =
{
	frontend:
	{
		init: !rs.registry.adminParameter,
		optionArray:
		{
			selector: 'a.rs-js-confirm'
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
		optionArray:
		{
			selector: 'a.rs-admin-js-cancel, a.rs-admin-js-delete, a.rs-admin-js-uninstall'
		}
	}
};

rs.modules.UnmaskPassword =
{
	frontend:
	{
		init: !rs.registry.adminParameter,
		optionArray:
		{
			selector: 'input.rs-js-password',
			className:
			{
				wrapper: 'rs-wrapper-unmask-password',
				button: 'rs-button-unmask-password',
				field: 'rs-field-unmask-password',
				active: 'rs-is-active'
			}
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
		optionArray:
		{
			selector: 'input.rs-admin-js-password',
			className:
			{
				wrapper: 'rs-admin-wrapper-unmask-password',
				button: 'rs-admin-button-unmask-password',
				field: 'rs-admin-field-unmask-password',
				active: 'rs-admin-is-active'
			}
		}
	}
};

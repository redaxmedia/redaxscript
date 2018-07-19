rs.modules.FormValidator =
{
	frontend:
	{
		init: true,
		config:
		{
			selector: 'form.rs-js-validate-form',
			element: '[required]',
			className:
			{
				fieldNote: 'rs-field-note',
				isError: 'rs-is-error'
			}
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token,
		config:
		{
			selector: 'form.rs-admin-js-validate-form',
			element: '[required]',
			className:
			{
				fieldNote: 'rs-admin-field-note',
				isError: 'rs-admin-is-error'
			}
		}
	}
};

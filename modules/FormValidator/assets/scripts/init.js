rs.modules.FormValidator =
{
	frontend:
	{
		init: true,
		optionArray:
		{
			selector: 'form.rs-js-validate',
			element:
			{
				required: '[required]'
			},
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
		optionArray:
		{
			selector: 'form.rs-admin-js-validate',
			element:
			{
				required: '[required]'
			},
			className:
			{
				fieldNote: 'rs-admin-field-note',
				isError: 'rs-admin-is-error'
			}
		}
	}
};

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
				box: 'div[contenteditable]',
				field: 'input, textarea'
			},
			className:
			{
				fieldNote: 'rs-field-note',
				isWarning: 'rs-is-warning',
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
				box: 'div[contenteditable]',
				field: 'input, textarea'
			},
			className:
			{
				fieldNote: 'rs-admin-field-note',
				isWarning: 'rs-admin-is-warning',
				isError: 'rs-admin-is-error'
			}
		}
	}
};

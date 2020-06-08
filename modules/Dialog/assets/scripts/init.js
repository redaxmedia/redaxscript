rs.modules.Dialog =
{
	frontend:
	{
		init: !rs.registry.adminParameter,
		optionArray:
		{
			element:
			{
				field: 'input.rs-js-input',
				buttonOk: 'button.rs-js-ok',
				buttonCancel: 'button.rs-js-cancel'
			},
			route:
			{
				alert: rs.registry.parameterRoute + 'module/dialog/alert',
				confirm: rs.registry.parameterRoute + 'module/dialog/confirm',
				prompt: rs.registry.parameterRoute + 'module/dialog/prompt'
			}
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
		optionArray:
		{
			element:
			{
				field: 'input.rs-admin-js-input',
				buttonOk: 'button.rs-admin-js-ok',
				buttonCancel: 'button.rs-admin-js-cancel'
			},
			route:
			{
				alert: rs.registry.parameterRoute + 'module/admin-dialog/alert',
				confirm: rs.registry.parameterRoute + 'module/admin-dialog/confirm',
				prompt: rs.registry.parameterRoute + 'module/admin-dialog/prompt'
			}
		}
	}
};

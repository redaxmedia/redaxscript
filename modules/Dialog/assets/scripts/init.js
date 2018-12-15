rs.modules.Dialog =
{
	frontend:
	{
		init: true,
		optionArray:
		{
			selector: 'a.rs-js-confirm',
			element:
			{
				buttonOk: 'button.js-ok',
				buttonCancel: 'button.js-cancel'
			},
			alertRoute: rs.registry.parameterRoute + 'module/dialog/alert',
			confirmRoute: rs.registry.parameterRoute + 'module/dialog/confirm',
			promptRoute: rs.registry.parameterRoute + 'module/dialog/prompt'
		}
	},
	backend:
	{
		init: rs.registry.loggedIn === rs.registry.token,
		optionArray:
		{
			selector: 'a.rs-admin-js-cancel, a.rs-admin-js-confirm, a.rs-admin-js-delete, a.rs-admin-js-uninstall',
			element:
			{
				buttonOk: 'button.js-admin-ok',
				buttonCancel: 'button.js-admin-cancel'
			},
			alertRoute: rs.registry.parameterRoute + 'module/admin-dialog/alert',
			confirmRoute: rs.registry.parameterRoute + 'module/admin-dialog/confirm',
			promptRoute: rs.registry.parameterRoute + 'module/admin-dialog/prompt'
		}
	}
};

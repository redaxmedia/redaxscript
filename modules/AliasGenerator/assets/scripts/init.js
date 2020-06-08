rs.modules.AliasGenerator =
{
	init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
	dependency: typeof window.getSlug === 'function',
	optionArray:
	{
		selector: 'form.rs-admin-js-alias',
		element:
		{
			input: 'input.rs-admin-js-alias-input',
			output: 'input.rs-admin-js-alias-output'
		}
	}
};

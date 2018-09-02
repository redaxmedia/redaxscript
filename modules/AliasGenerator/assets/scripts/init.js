rs.modules.AliasGenerator =
{
	init: true,
	dependency: typeof window.getSlug === 'function',
	config:
	{
		selector: 'form.rs-admin-js-alias',
		element:
		{
			input: 'input.rs-admin-js-alias-input',
			output: 'input.rs-admin-js-alias-output'
		}
	}
};

rs.templates.admin =
{
	alias:
	{
		init: true,
		dependency: typeof window.getSlug === 'function',
		config:
		{
			selector: 'form input.rs-admin-js-alias-input, form input.rs-admin-js-alias-output',
			element:
			{
				output: 'input.rs-admin-js-alias-output'
			}
		}
	}
};

rs.templates.admin.alias.generate = config =>
{
	const CONFIG = {...rs.templates.admin.alias.config, ...config};
	const form = document.querySelector(CONFIG.selector);

	if (form)
	{
		const input = form.querySelector(CONFIG.element.input);
		const output = form.querySelector(CONFIG.element.output);
		const validate = new CustomEvent('validate');

		/* handel input */

		input.addEventListener('input', () =>
		{
			if (input.value)
			{
				const aliasValue = window.getSlug(input.value);

				if (aliasValue)
				{
					output.value = aliasValue;
					output.dispatchEvent(validate);
				}
			}
			else
			{
				output.value = null;
				output.dispatchEvent(validate);
			}
		});

		/* handel input */

		output.addEventListener('input', () =>
		{
			if (output.value)
			{
				const aliasValue = window.getSlug(output.value);

				if (aliasValue)
				{
					output.value = aliasValue;
					output.dispatchEvent(validate);
				}
			}
		});
	}
};

/* run as needed */

if (rs.templates.admin.alias.init && rs.templates.admin.alias.dependency)
{
	rs.templates.admin.alias.generate(rs.templates.admin.alias.config);
}

rs.modules.AliasGenerator.generate = optionArray =>
{
	const OPTION =
	{
		...rs.modules.AliasGenerator.optionArray,
		...optionArray
	};
	const form = document.querySelector(OPTION.selector);

	if (form)
	{
		const input = form.querySelector(OPTION.element.input);
		const output = form.querySelector(OPTION.element.output);
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

if (rs.modules.AliasGenerator.init && rs.modules.AliasGenerator.dependency)
{
	rs.modules.AliasGenerator.generate(rs.modules.AliasGenerator.optionArray);
}

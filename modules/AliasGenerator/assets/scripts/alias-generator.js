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
		const inputEvent = new Event('input');

		/* handel input */

		input.addEventListener('input', () =>
		{
			if (input.value)
			{
				const aliasValue = window.getSlug(input.value);

				if (aliasValue)
				{
					output.value = aliasValue;
					output.dispatchEvent(inputEvent);
				}
			}
			else
			{
				output.value = null;
				output.dispatchEvent(inputEvent);
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
					output.dispatchEvent(inputEvent);
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

rs.modules.AliasGenerator.generate = optionArray =>
{
	const OPTION =
	{
		...rs.modules.AliasGenerator.optionArray,
		...optionArray
	};
	const formElement = document.querySelector(OPTION.selector);

	if (formElement)
	{
		const inputElement = formElement.querySelector(OPTION.element.input);
		const outputElement = formElement.querySelector(OPTION.element.output);
		const inputEvent = new Event('input');

		/* handel input */

		inputElement.addEventListener('input', () =>
		{
			if (inputElement.value)
			{
				const aliasValue = window.getSlug(inputElement.value);

				if (aliasValue)
				{
					outputElement.value = aliasValue;
					outputElement.dispatchEvent(inputEvent);
				}
			}
			else
			{
				outputElement.value = null;
				outputElement.dispatchEvent(inputEvent);
			}
		});

		/* handel input */

		outputElement.addEventListener('input', () =>
		{
			if (outputElement.value)
			{
				const aliasValue = window.getSlug(outputElement.value);

				if (aliasValue)
				{
					outputElement.value = aliasValue;
					outputElement.dispatchEvent(inputEvent);
				}
			}
		});
	}
};

/* run as needed */

if (rs.modules.AliasGenerator.init && rs.modules.AliasGenerator.dependency)
{
	rs.modules.AliasGenerator.generate();
}

rs.templates.install.behavior.process = optionArray =>
{
	const OPTION =
	{
		...rs.templates.install.behavior.optionArray,
		...optionArray
	};
	const formElement = document.querySelector(OPTION.selector);
	const fieldTypeElement = formElement.querySelector(OPTION.element.fieldType);
	const fieldToggleList = formElement.querySelectorAll(OPTION.element.fieldToggle);
	const changeEvent = new Event('change');

	/* listen on change */

	fieldTypeElement.addEventListener('change', () =>
	{
		fieldToggleList.forEach(fieldToggleElement =>
		{
			if (fieldTypeElement.value === 'sqlite')
			{
				fieldToggleElement.value = null;
				fieldToggleElement.parentElement.style.display = 'none';
				if (fieldToggleElement.hasAttribute('required'))
				{
					fieldToggleElement.setAttribute('data-required', 'required');
					fieldToggleElement.removeAttribute('required');
				}
			}
			else
			{
				fieldToggleElement.parentElement.style.display = null;
				if (fieldToggleElement.hasAttribute('data-required'))
				{
					fieldToggleElement.setAttribute('required', 'required');
					fieldToggleElement.removeAttribute('data-required');
				}
			}
		});
	});
	fieldTypeElement.dispatchEvent(changeEvent);
};

/* run as needed */

if (rs.templates.install.behavior.init)
{
	rs.templates.install.behavior.process(rs.templates.install.behavior.optionArray);
}

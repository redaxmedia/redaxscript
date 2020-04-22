rs.templates.install.behavior.process = optionArray =>
{
	const OPTION =
	{
		...rs.templates.install.behavior.optionArray,
		...optionArray
	};
	const form = document.querySelector(OPTION.selector);
	const fieldType = form.querySelector(OPTION.element.fieldType);
	const fieldToggleList = form.querySelectorAll(OPTION.element.fieldToggle);
	const changeEvent = new Event('change');

	/* listen on change */

	fieldType.addEventListener('change', () =>
	{
		fieldToggleList.forEach(field =>
		{
			if (fieldType.value === 'sqlite')
			{
				field.value = null;
				field.parentElement.style.display = 'none';
				if (field.hasAttribute('required'))
				{
					field.setAttribute('data-required', 'required');
					field.removeAttribute('required');
				}
			}
			else
			{
				field.parentElement.style.display = null;
				if (field.hasAttribute('data-required'))
				{
					field.setAttribute('required', 'required');
					field.removeAttribute('data-required');
				}
			}
		});
	});
	fieldType.dispatchEvent(changeEvent);
};

/* run as needed */

if (rs.templates.install.behavior.init)
{
	rs.templates.install.behavior.process(rs.templates.install.behavior.optionArray);
}

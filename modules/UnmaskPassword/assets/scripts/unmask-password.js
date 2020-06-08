rs.modules.UnmaskPassword.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.UnmaskPassword.getOption(),
		...optionArray
	};
	const fieldList = document.querySelectorAll(OPTION.selector);

	if (fieldList)
	{
		fieldList.forEach(field =>
		{
			field.addEventListener('focusin', () =>
			{
				field.setAttribute('type', 'text');
			});
			field.addEventListener('focusout', () =>
			{
				field.setAttribute('type', 'password');
			});
		});
	}
};

rs.modules.UnmaskPassword.getOption = () =>
{
	if (rs.modules.UnmaskPassword.frontend.init)
	{
		return rs.modules.UnmaskPassword.frontend.optionArray;
	}
	if (rs.modules.UnmaskPassword.backend.init)
	{
		return rs.modules.UnmaskPassword.backend.optionArray;
	}
};

/* run as needed */

if (rs.modules.UnmaskPassword.frontend.init || rs.modules.UnmaskPassword.backend.init)
{
	rs.modules.UnmaskPassword.process();
}

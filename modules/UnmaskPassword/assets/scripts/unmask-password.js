rs.modules.UnmaskPassword.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.UnmaskPassword.optionArray,
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

/* run as needed */

if (rs.modules.UnmaskPassword.frontend.init)
{
	rs.modules.UnmaskPassword.process(rs.modules.UnmaskPassword.frontend.optionArray);
}
if (rs.modules.UnmaskPassword.backend.init)
{
	rs.modules.UnmaskPassword.process(rs.modules.UnmaskPassword.backend.optionArray);
}

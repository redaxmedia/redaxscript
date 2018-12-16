rs.modules.FormValidator.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.FormValidator.optionArray,
		...optionArray
	};
	const formList = document.querySelectorAll(OPTION.selector);
	const validateArray =
	[
		'input',
		'validate'
	];

	if (formList)
	{
		formList.forEach(form =>
		{
			/* handle validate */

			form.querySelectorAll(OPTION.element.required).forEach(field =>
			{
				field.classList.add(OPTION.className.fieldNote);
				validateArray.forEach(validateEvent =>
				{
					field.addEventListener(validateEvent, event =>
					{
						event.target.validity.valid ? event.target.classList.remove(OPTION.className.isError) : event.target.classList.add(OPTION.className.isError);
					});
				});
				field.addEventListener('invalid', event =>
				{
					event.target.classList.add(OPTION.className.isError);
				});
			});

			/* handle reset */

			form.addEventListener('reset', () =>
			{
				form.querySelectorAll(OPTION.element.required).forEach(field => field.classList.remove(OPTION.className.isError));
			});
		});
	}
};

/* run as needed */

if (rs.modules.FormValidator.frontend.init)
{
	rs.modules.FormValidator.process(rs.modules.FormValidator.frontend.optionArray);
}
if (rs.modules.FormValidator.backend.init)
{
	rs.modules.FormValidator.process(rs.modules.FormValidator.backend.optionArray);
}

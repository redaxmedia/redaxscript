rs.modules.FormValidator.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.FormValidator.optionArray,
		...optionArray
	};
	const form = document.querySelector(OPTION.selector);

	if (form)
	{
		/* handle validate */

		form.querySelectorAll(OPTION.element.required).forEach(field =>
		{
			const validateArray =
			[
				'input',
				'validate'
			];

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

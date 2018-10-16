rs.modules.FormValidator.validate = config =>
{
	const CONFIG =
	{
		...rs.modules.FormValidator.config,
		...config
	};
	const form = document.querySelector(CONFIG.selector);

	if (form)
	{
		/* handle validate */

		form.querySelectorAll(CONFIG.element.required).forEach(field =>
		{
			const validateArray =
			[
				'input',
				'validate'
			];

			field.classList.add(CONFIG.className.fieldNote);
			validateArray.forEach(validateEvent =>
			{
				field.addEventListener(validateEvent, event =>
				{
					event.target.validity.valid ? event.target.classList.remove(CONFIG.className.isError) : event.target.classList.add(CONFIG.className.isError);
				});
			});
			field.addEventListener('invalid', event =>
			{
				event.target.classList.add(CONFIG.className.isError);
			});
		});

		/* handle reset */

		form.addEventListener('reset', () =>
		{
			form.querySelectorAll(CONFIG.element.required).forEach(field => field.classList.remove(CONFIG.className.isError));
		});
	}
};

/* run as needed */

if (rs.modules.FormValidator.frontend.init)
{
	rs.modules.FormValidator.validate(rs.modules.FormValidator.frontend.config);
}
if (rs.modules.FormValidator.backend.init)
{
	rs.modules.FormValidator.validate(rs.modules.FormValidator.backend.config);
}

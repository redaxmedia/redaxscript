rs.modules.FormValidator.validate = config =>
{
	const CONFIG = {...rs.modules.FormValidator.config, ...config};
	const form = document.querySelector(CONFIG.selector);

	if (form)
	{
		/* handle validate */

		form.querySelectorAll(CONFIG.element).forEach(fieldValue =>
		{
			const validateArray =
			[
				'input',
				'validate'
			];

			fieldValue.classList.add(CONFIG.className.fieldNote);
			validateArray.forEach(event =>
			{
				fieldValue.addEventListener(event, () =>
				{
					fieldValue.validity.valid ? fieldValue.classList.remove(CONFIG.className.isError) : fieldValue.classList.add(CONFIG.className.isError);
				});
			});
			fieldValue.addEventListener('invalid', () =>
			{
				fieldValue.classList.add(CONFIG.className.isError);
			});
		});

		/* handle reset */

		form.addEventListener('reset', () =>
		{
			form.querySelectorAll(CONFIG.element).forEach(fieldValue => fieldValue.classList.remove(CONFIG.className.isError));
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

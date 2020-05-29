rs.modules.FormValidator.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.FormValidator.optionArray,
		...optionArray
	};
	const formList = document.querySelectorAll(OPTION.selector);

	if (formList)
	{
		formList.forEach(form =>
		{
			/* handle validate */

			form.querySelectorAll(OPTION.element.field).forEach(field =>
			{
				field.parentElement.querySelectorAll(OPTION.element.box).forEach(box =>
				{
					box.classList.add(OPTION.className.fieldNote);
				});
				field.classList.add(OPTION.className.fieldNote);
				[
					'input',
					'invalid'
				]
				.forEach(eventType =>
				{
					field.addEventListener(eventType, () =>
					{
						field.parentElement.querySelectorAll(OPTION.element.box).forEach(box =>
						{
							box.classList.remove(OPTION.className.isError);
						});
						field.classList.remove(OPTION.className.isWarning);
						field.classList.remove(OPTION.className.isError);
						if (field.validity.valueMissing)
						{
							field.parentElement.querySelectorAll(OPTION.element.box).forEach(box =>
							{
								box.classList.add(OPTION.className.isError);
							});
							field.classList.add(OPTION.className.isError);
						}
						else if (!field.validity.valid)
						{
							field.classList.add(OPTION.className.isWarning);
						}
					});
				});
			});

			/* handle reset */

			form.addEventListener('reset', () =>
			{
				form.querySelectorAll(OPTION.element.field).forEach(field =>
				{
					field.classList.remove(OPTION.className.isWarning);
					field.classList.remove(OPTION.className.isError);
				});
			});
		});
	}
};

/* run as needed */

if (rs.modules.FormValidator.frontend.init)
{
	window.addEventListener('load', () => rs.modules.FormValidator.process(rs.modules.FormValidator.frontend.optionArray));
}
if (rs.modules.FormValidator.backend.init)
{
	window.addEventListener('load', () => rs.modules.FormValidator.process(rs.modules.FormValidator.backend.optionArray));
}

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
		formList.forEach(formElement =>
		{
			/* handle validate */

			formElement.querySelectorAll(OPTION.element.field).forEach(fieldElement =>
			{
				if (fieldElement.previousSibling && fieldElement.previousSibling.isContentEditable)
				{
					fieldElement.previousSibling.classList.add(OPTION.className.fieldNote);
				}
				fieldElement.classList.add(OPTION.className.fieldNote);
				[
					'input',
					'invalid'
				]
				.forEach(eventType =>
				{
					fieldElement.addEventListener(eventType, () =>
					{
						if (fieldElement.previousSibling && fieldElement.previousSibling.isContentEditable)
						{
							fieldElement.previousSibling.classList.remove(OPTION.className.isError);
						}
						fieldElement.classList.remove(OPTION.className.isWarning);
						fieldElement.classList.remove(OPTION.className.isError);
						if (fieldElement.validity.valueMissing)
						{
							if (fieldElement.previousSibling && fieldElement.previousSibling.isContentEditable)
							{
								fieldElement.previousSibling.classList.add(OPTION.className.isError);
							}
							fieldElement.classList.add(OPTION.className.isError);
						}
						else if (!fieldElement.validity.valid)
						{
							fieldElement.classList.add(OPTION.className.isWarning);
						}
					});
				});
			});

			/* handle reset */

			formElement.addEventListener('reset', () =>
			{
				formElement.querySelectorAll(OPTION.element.field).forEach(fieldElement =>
				{
					if (fieldElement.previousSibling && fieldElement.previousSibling.isContentEditable)
					{
						fieldElement.previousSibling.classList.remove(OPTION.className.isError);
					}
					fieldElement.classList.remove(OPTION.className.isWarning);
					fieldElement.classList.remove(OPTION.className.isError);
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

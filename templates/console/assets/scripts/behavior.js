rs.templates.console.history = [];
rs.templates.console.behavior.process = optionArray =>
{
	const OPTION =
	{
		...rs.templates.console.behavior.optionArray,
		...optionArray
	};
	const formElement = document.querySelector(OPTION.selector);
	const boxElement = document.querySelector(OPTION.element.box);
	const labelElement = formElement.querySelector(OPTION.element.label);
	const fieldElement = formElement.querySelector(OPTION.element.field);

	/* handel submit */

	formElement.addEventListener('submit', event =>
	{
		boxElement.innerHTML += labelElement.innerText + ' ' + fieldElement.value + OPTION.eol;
		fetch(location.href,
		{
			credentials: 'same-origin',
			method: 'POST',
			headers:
			{
				'Content-Type': 'application/json',
				'X-Requested-With': 'XMLHttpRequest'
			},
			body: JSON.stringify(
			{
				argv: fieldElement.value
			})
		})
		.then(response => response.text())
		.then(response =>
		{
			boxElement.innerHTML += response;
			window.scrollTo(0, document.body.scrollHeight);
		})
		.catch(() => null);
		formElement.reset();
		event.preventDefault();
	});

	/* handle click */

	window.addEventListener('click', () =>
	{
		fieldElement.focus();
	});

	/* handle keydown */

	fieldElement.addEventListener('keydown', event =>
	{
		const currentIndex = rs.templates.console.history.findIndex(value => value.selected);
		const nextIndex = currentIndex + 1;
		const previousIndex = currentIndex - 1;

		if (event.key === 'ArrowUp' && rs.templates.console.history[currentIndex])
		{
			if (rs.templates.console.history[nextIndex])
			{
				rs.templates.console.history[currentIndex].selected = false;
				rs.templates.console.history[nextIndex].selected = true;
			}
			requestAnimationFrame(() => fieldElement.value = rs.templates.console.history[currentIndex].value);
		}
		if (event.key === 'ArrowDown' && rs.templates.console.history[currentIndex])
		{
			if (rs.templates.console.history[previousIndex])
			{
				rs.templates.console.history[currentIndex].selected = false;
				rs.templates.console.history[previousIndex].selected = true;
			}
			requestAnimationFrame(() => fieldElement.value = rs.templates.console.history[currentIndex].value);
		}
		if (event.key === 'Tab')
		{
			event.preventDefault();
		}
		if (event.key === 'Enter')
		{
			if (fieldElement.value)
			{
				rs.templates.console.history.map(item => item.selected = false);
				rs.templates.console.history.unshift(
				{
					selected: true,
					value: fieldElement.value
				});
			}
		}
	});
};

/* run as needed */

if (rs.templates.console.behavior.init)
{
	rs.templates.console.behavior.process(rs.templates.console.behavior.optionArray);
}

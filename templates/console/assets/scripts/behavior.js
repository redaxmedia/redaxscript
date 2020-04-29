rs.templates.console.history = [];
rs.templates.console.behavior.process = optionArray =>
{
	const OPTION =
	{
		...rs.templates.console.behavior.optionArray,
		...optionArray
	};
	const form = document.querySelector(OPTION.selector);
	const box = document.querySelector(OPTION.element.box);
	const label = form.querySelector(OPTION.element.label);
	const field = form.querySelector(OPTION.element.field);

	/* handel submit */

	form.addEventListener('submit', event =>
	{
		box.innerHTML += label.innerText + ' ' + field.value + OPTION.eol;
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
				argv: field.value
			})
		})
		.then(response => response.text())
		.then(response =>
		{
			box.innerHTML += response;
			window.scrollTo(0, document.body.scrollHeight);
		})
		.catch(() => null);
		form.reset();
		event.preventDefault();
	});

	/* handle click */

	window.addEventListener('click', () =>
	{
		field.focus();
	});

	/* handle keydown */

	field.addEventListener('keydown', event =>
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
			requestAnimationFrame(() => field.value = rs.templates.console.history[currentIndex].value);
		}
		if (event.key === 'ArrowDown' && rs.templates.console.history[currentIndex])
		{
			if (rs.templates.console.history[previousIndex])
			{
				rs.templates.console.history[currentIndex].selected = false;
				rs.templates.console.history[previousIndex].selected = true;
			}
			requestAnimationFrame(() => field.value = rs.templates.console.history[currentIndex].value);
		}
		if (event.key === 'Tab')
		{
			event.preventDefault();
		}
		if (event.key === 'Enter')
		{
			if (field.value)
			{
				rs.templates.console.history.map(item => item.selected = false);
				rs.templates.console.history.unshift(
				{
					selected: true,
					value: field.value
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

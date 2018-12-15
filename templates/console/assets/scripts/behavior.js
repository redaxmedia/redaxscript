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
				'Content-Type': 'application/x-www-form-urlencoded',
				'X-Requested-With': 'XMLHttpRequest'
			},
			body: 'argv=' + field.value

		})
		.then(response => response.text())
		.then(response =>
		{
			box.innerHTML += response;
			window.scrollTo(0, document.body.scrollHeight);
		});
		form.reset();
		event.preventDefault();
	});

	/* handle click */

	window.addEventListener('click', () =>
	{
		field.focus();
	});
};

/* run as needed */

if (rs.templates.console.behavior.init)
{
	rs.templates.console.behavior.process(rs.templates.console.behavior.optionArray);
}

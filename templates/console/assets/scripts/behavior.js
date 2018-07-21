rs.templates.console.behavior.listen = config =>
{
	const CONFIG = {...rs.templates.console.behavior.config, ...config};
	const form = document.querySelector(CONFIG.selector);
	const box = document.querySelector(CONFIG.element.box);
	const label = form.querySelector(CONFIG.element.label);
	const field = form.querySelector(CONFIG.element.field);

	/* handel submit */

	form.addEventListener('submit', event =>
	{
		box.innerHTML += label.innerText + ' ' + field.value + CONFIG.eol;
		fetch(location.href,
		{
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
	rs.templates.console.behavior.listen(rs.templates.console.behavior.config);
}

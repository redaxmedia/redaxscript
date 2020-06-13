rs.modules.ImageUpload.list = optionArray =>
{
	const OPTION =
	{
		...rs.modules.ImageUpload.optionArray,
		...optionArray
	};

	return fetch(OPTION.route.list).then(response => response.json());
};

rs.modules.ImageUpload.upload = (body, optionArray) =>
{
	const OPTION =
	{
		...rs.modules.ImageUpload.optionArray,
		...optionArray
	};

	return fetch(OPTION.route.upload,
	{
		credentials: 'same-origin',
		method: 'POST',
		body
	}).then(response => response.json());
};

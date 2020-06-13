rs.modules.ImageUpload =
{
	init: rs.registry.loggedIn === rs.registry.token && rs.registry.adminParameter,
	optionArray:
	{
		route:
		{
			list: rs.registry.parameterRoute + 'module/image-upload/list/' + rs.registry.token,
			upload: rs.registry.parameterRoute + 'module/image-upload/upload/' + rs.registry.token
		}
	}
};

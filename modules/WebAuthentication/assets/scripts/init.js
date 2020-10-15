rs.modules.WebAuthentication =
{
	init: true,
	optionArray:
	{
		relayParty:
		{
			name: rs.language._package.name
		},
		credential:
		{
			transportArray:
			[
				'usb',
				'nfc',
				'ble'
			],
			type: 'public-key',
			algorithm: -7
		},
		attestation: 'indirect',
		route:
		{
			create: rs.registry.parameterRoute + 'module/web-authentication/create/' + rs.registry.token,
			read: rs.registry.parameterRoute + 'module/web-authentication/read/' + rs.registry.token,
			delete: rs.registry.parameterRoute + 'module/web-authentication/delete/' + rs.registry.token,
			login: rs.registry.parameterRoute + 'module/web-authentication/login/' + rs.registry.token
		}
	}
};

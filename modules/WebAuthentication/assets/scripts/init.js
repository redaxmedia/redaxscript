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
			register: rs.registry.parameterRoute + 'module/Web-authentication/register/' + rs.registry.token,
			login: rs.registry.parameterRoute + 'module/Web-authentication/login/' + rs.registry.token
		}
	}
};

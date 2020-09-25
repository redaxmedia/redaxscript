rs.modules.WebAuthentication.create = (userArray, optionArray) =>
{
	const OPTION =
	{
		...rs.modules.WebAuthentication.optionArray,
		...optionArray
	};

	return navigator.credentials.create(
	{
		publicKey:
		{
			user:
			{
				id: new Uint8Array(userArray.id),
				name: userArray.user,
				displayName: userArray.name
			},
			rp: OPTION.relayParty,
			pubKeyCredParams:
			[
				{
					type: OPTION.credential.type,
					alg: OPTION.credential.algorithm
				}
			],
			attestation: OPTION.attestation,
			challenge: rs.modules.WebAuthentication.getChallenge()
		}
	});
};

rs.modules.WebAuthentication.get = optionArray =>
{
	const OPTION =
	{
		...rs.modules.WebAuthentication.optionArray,
		...optionArray
	};

	return navigator.credentials.get(
	{
		publicKey:
		{
			allowCredentials:
			[
				{
					id: null,
					transports: OPTION.credential.transportArray,
					type: OPTION.credential.type
				}
			],
			challenge: rs.modules.WebAuthentication.getChallenge()
		}
	});
};

rs.modules.WebAuthentication.register = registerArray =>
{
	console.log('FETCH TO BACKEND TO REGISTER USER', registerArray);
};

rs.modules.WebAuthentication.login = loginArray =>
{
	console.log('FETCH TO BACKEND TO LOGIN USER', loginArray);
};

rs.modules.WebAuthentication.process = () =>
{
	rs.modules.WebAuthentication.create(
	{
		id: 16,
		user: 'admin',
		name: 'God Admin'
	})
	.then(response => rs.modules.WebAuthentication.register(
	{
		user: 16,
		id: response.id
	}))
	.catch(error => console.error(error));
};

rs.modules.WebAuthentication.getChallenge = () =>
{
	return new Uint8Array(rs.registry.token).buffer;
};

/* run as needed */

if (rs.modules.WebAuthentication.init)
{
	rs.modules.WebAuthentication.process();
}

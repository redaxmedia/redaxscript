rs.modules.Experiments =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof window.cxApi === 'object',
	config:
	{
		action:
		[
			() => document.documentElement.classList.add('rs-no-variation'),
			() => document.documentElement.classList.add('rs-is-variation-1'),
			() => document.documentElement.classList.add('rs-is-variation-2')
		]
	}
};

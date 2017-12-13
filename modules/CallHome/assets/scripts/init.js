/**
 * @tableofcontents
 *
 * 1. call home
 */

/** @section 1. call home */

rs.modules.callHome =
{
	init: rs.registry.loggedIn === rs.registry.token && rs.registry.firstParameter === 'admin',
	dependency: typeof ga === 'function',
	options:
	{
		id: 'UA-16122280-10',
		cookieDomain: 'auto',
		anonymizeIp: true
	}
};

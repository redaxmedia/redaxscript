/**
 * @tableofcontents
 *
 * 1. analytics
 */

/** @section 1. analytics */

rs.modules.analytics =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof ga === 'function',
	selector: 'a.rs-js-track-click, button.rs-js-track-click',
	options:
	{
		id: 'UA-00000000-0',
		cookieDomain: 'auto',
		anonymizeIp: true
	}
};

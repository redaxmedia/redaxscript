/**
 * @tableofcontents
 *
 * 1. analytics
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. analytics */

rs.modules.analytics =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof _gaq === 'object',
	selector: 'a.js_track_click, button.js_track_click',
	options:
	{
		id: 'UA-00000000-0',
		url: 'example.com'
	}
};

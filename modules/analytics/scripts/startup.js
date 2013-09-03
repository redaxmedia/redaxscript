/**
 * @tableofcontents
 *
 * 1. analytics
 *
 * @since 2.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. analytics */

r.modules.analytics =
{
	startup: true,
	selector: 'a.js_track_click',
	options:
	{
		id: 'UA-00000000-0',
		url: 'domain.com'
	}
};
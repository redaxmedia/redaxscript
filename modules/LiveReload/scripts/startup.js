/**
 * @tableofcontents
 *
 * 1. live reload
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. live reload */

rxs.modules.liveReload =
{
	startup: true,
	options:
	{
		className:
		{
			liveReloadBox: 'box_live_reload'
		},
		url: 'loader/styles',
		duration: 500
	}
};
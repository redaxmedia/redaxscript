/**
 * @tableofcontents
 *
 * 1. admin dock
 * 2. admin panel
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. admin dock */

rs.plugins.adminDock =
{
	init: true,
	selector: 'div.rs-admin-js-dock',
	options:
	{
		element:
		{
			dockLink: 'a.rs-admin-js-link-dock',
			dockDescription: 'span.rs-admin-js-description-dock',
			dockDescriptionHTML: '<span class="rs-admin-js-description-dock rs-admin-description-dock"></span>'
		},
		vibrate: 100
	}
};

/* @section 2. admin panel */

rs.plugins.adminPanel =
{
	init: true,
	selector: 'ul.rs-admin-js-list-panel',
	options:
	{
		element:
		{
			panelItem: 'li.rs-admin-js-item-panel'
		},
		timeout: 1000,
		duration: 200,
		vibrate: 100
	}
};
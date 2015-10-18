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
	selector: 'div.admin-js-dock-admin',
	options:
	{
		element:
		{
			dockLink: 'a.admin-js-link-dock-admin',
			dockDescription: 'span.admin-js-description-dock-admin',
			dockDescriptionHTML: '<span class="admin-js-description-dock-admin admin-description-dock-admin"></span>'
		},
		vibrate: 100
	}
};

/* @section 2. admin panel */

rs.plugins.adminPanel =
{
	init: true,
	selector: 'ul.admin-js-list-panel-admin',
	options:
	{
		element:
		{
			panelItem: 'li.admin-js-item-panel-admin'
		},
		timeout: 1000,
		duration: 300,
		vibrate: 100
	}
};
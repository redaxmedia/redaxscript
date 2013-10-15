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

r.plugins.adminDock =
{
	startup: true,
	selector: 'div.js_dock_admin',
	options:
	{
		element:
		{
			dockLink: 'a.js_link_dock_admin',
			dockDescription: 'span.js_description_dock_admin',
			dockDescriptionHTML: '<span class="js_description_dock_admin description_dock_admin"></span>'
		},
		vibrate: 100
	}
};

/* @section 2. admin panel */

r.plugins.adminPanel =
{
	startup: true,
	selector: 'ul.js_list_panel_admin',
	options:
	{
		element:
		{
			panelItem: 'li.js_item_panel_admin'
		},
		timeout: 1000,
		duration: 300,
		vibrate: 100
	}
};
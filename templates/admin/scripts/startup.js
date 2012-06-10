/* admin dock */

r.plugin.adminDock =
	{
		startup: 1,
		selector: 'div.js_dock_admin',
		options:
		{
			element:
			{
				dockLink: 'a.js_link_dock_admin',
				dockDescription: 'span.js_description_dock_admin',
				dockDescriptionHTML: '<span class="js_description_dock_admin description_dock_admin"></span>'
			}
		}
	};

/* admin panel */

r.plugin.adminPanel =
	{
		startup: 1,
		selector: '#panel_admin',
		options:
		{
			element:
			{
				panelBox: 'div.js_box_panel_admin',
				panelBar: 'div.js_panel_bar_admin'
			},
			related: '#header',
			duration: 1000
		}
	};
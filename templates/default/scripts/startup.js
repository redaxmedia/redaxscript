/* extend redaxscript object */

r.plugin.logoEffect =
	{
		startup: 1,
		selector: 'h1.js_logo_effect_trigger',
		options:
		{
			related: 'div.js_logo_website_effect',
			duration: 500
		}
	};

/* startup */

r.startup();
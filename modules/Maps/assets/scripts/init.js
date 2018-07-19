rs.modules.Maps =
{
	init: !rs.registry.adminParameter,
	dependency: typeof window.google === 'object' && typeof window.google.maps === 'object',
	config:
	{
		selector: 'div.rs-js-map'
	}
};

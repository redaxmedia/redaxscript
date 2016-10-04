/**
 * @tableofcontents
 *
 * 1. maps
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. maps */

rs.modules.maps =
{
	init: !rs.registry.adminParameter,
	dependency: typeof window.google === 'object' && typeof window.google.maps === 'object',
	selector: 'div.rs-js-map',
	options:
	{
		general:
		{
			zoom: 1,
			center: new window.google.maps.LatLng(0, 0),
			mapTypeId: window.google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			panControl: true,
			overviewMapControl: false,
			zoomControl: true
		},
		styles:
		[
			{
				featureType: 'all',
				stylers:
				[
					{
						saturation: -100
					}
				]
			},
			{
				featureType: 'poi',
				stylers:
				[
					{
						visibility: 'off'
					}
				]
			},
			{
				featureType: 'transit',
				stylers:
				[
					{
						visibility: 'off'
					}
				]
			},
			{
				featureType: 'road.highway',
				elementType: 'labels',
				stylers:
				[
					{
						visibility: 'off'
					}
				]
			},
			{
				featureType: 'water',
				stylers:
				[
					{
						color: '#0090db'
					}
				]
			}
		],
		marker: true,
		deBranding: true
	}
};

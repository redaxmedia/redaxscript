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
				featureType: 'administrative',
				elementType: 'labels.text.fill',
				stylers:
				[
					{
						color: '#333333'
					}
				]
			},
			{
				featureType: 'landscape',
				stylers:
				[
					{
						color: '#f5f5f5'
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
				featureType: 'road',
				stylers:
				[
					{
						saturation: -100
					},
					{
						lightness: 45
					}
				]
			},
			{
				featureType: 'road.highway',
				stylers:
				[
					{
						visibility: 'simplified'
					}
				]
			},
			{
				featureType: 'road.arterial',
				elementType: 'labels.icon',
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
				featureType: 'water',
				stylers:
				[
					{
						color: '#3873b5'
					}
				]
			}
		],
		marker: true
	}
};

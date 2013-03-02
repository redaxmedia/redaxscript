/**
 * @tableofcontents
 *
 * 1. maps
 */

/* @section 1. maps */

r.module.maps =
{
	startup: true,
	selector: 'div.js_map',
	options:
	{
		general:
		{
			zoom: 6,
			center: new google.maps.LatLng(51, 3),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			panControl: true,
			scaleControl: false,
			streetViewControl: false,
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
				featureType: 'water',
				stylers:
				[
					{
						color: '#0090db'
					}
				]
			}
		]
	}
};
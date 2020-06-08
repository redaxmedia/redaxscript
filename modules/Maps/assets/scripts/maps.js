rs.modules.Maps.process = optionArray =>
{
	const OPTION =
	{
		...rs.modules.Maps.optionArray,
		...optionArray
	};

	document.querySelectorAll(OPTION.selector).forEach(map =>
	{
		if (map)
		{
			const data = map.dataset;
			const latitude = Number(data.latitude);
			const longitude = Number(data.longitude);
			const zoom = Number(data.zoom);

			/* override config */

			if (latitude && longitude)
			{
				OPTION.maps.center = new window.google.maps.LatLng(latitude, longitude);
			}
			if (zoom)
			{
				OPTION.maps.zoom = zoom;
			}

			/* handle map */

			const mapInstance = new window.google.maps.Map(map, OPTION.maps);

			if (OPTION.marker)
			{
				new window.google.maps.Marker(
				{
					position: OPTION.maps.center,
					map: mapInstance
				});
			}
		}
	});
};

/* run as needed */

if (rs.modules.Maps.init && rs.modules.Maps.dependency)
{
	rs.modules.Maps.process();
}

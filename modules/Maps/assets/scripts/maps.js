rs.modules.Maps.execute = config =>
{
	const CONFIG =
	{
		...rs.modules.Maps.config,
		...config
	};

	document.querySelectorAll(CONFIG.selector).forEach(map =>
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
				CONFIG.maps.center = new window.google.maps.LatLng(latitude, longitude);
			}
			if (zoom)
			{
				CONFIG.maps.zoom = zoom;
			}

			/* handle map */

			const mapInstance = new window.google.maps.Map(map, CONFIG.maps);

			if (CONFIG.marker)
			{
				new window.google.maps.Marker(
				{
					position: CONFIG.maps.center,
					map: mapInstance
				});
			}
		}
	});
};

/* run as needed */

if (rs.modules.Maps.init && rs.modules.Maps.dependency)
{
	rs.modules.Maps.execute(rs.modules.Maps.config);
}

<?php

/**
 * lazy load loader start
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function lazy_load_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/lazy_load/styles/lazy_load.css';
	$loader_modules_scripts[] = 'modules/lazy_load/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/lazy_load/scripts/lazy_load.js';
}

/**
 * lazy load
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string|array $src
 * @param array $options
 * @return string
 */

function lazy_load($src= '', $options = '')
{
	/* multiserve images */

	if (is_array($src))
	{
		$allowed_src = array(
			'desktop',
			'tablet',
			'mobile'
		);

		/* handle each source */

		foreach ($src as $key => $value)
		{
			if (in_array($key, $allowed_src) && constant('MY_' . strtoupper($key)) && file_exists($value))
			{
				$image_route = $value;
			}
		}
	}

	/* else single image */

	else if (file_exists($src))
	{
		$image_route = $src;
	}

	/* image dimensions */

	$image_dimensions = getimagesize($image_route);
	$image_ratio = $image_dimensions[1] / $image_dimensions[0] * 100;

	/* define option variables */

	if ($options)
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* build class string */

	if ($option_class)
	{
		$class_string = ' class="js_lazy_load lazy_load ' . $option_class . '"';
	}
	else
	{
		$class_string = ' class="js_lazy_load lazy_load"';
	}

	/* build alt string */

	if ($option_alt)
	{
		$alt_string = ' alt="' . $option_alt . '"';
	}

	/* collect output */

	if ($image_route)
	{
		$output = '<img src="' . LAZY_LOAD_IMAGE . '" data-src="' . $image_route . '"' . $class_string . $alt_string . ' />';

		/* wrap placeholder */

		if (LAZY_LOAD_PLACEHOLDER)
		{
			$output = '<div class="placeholder_lazy_load" style="padding-bottom:' . $image_ratio . '%">' . $output . '</div>';
		}

		/* noscript fallback */

		$output .= '<noscript><img src="' . $image_route . '"' . $class_string . $alt_string . ' /></noscript>';
		echo $output;
	}
}
?>
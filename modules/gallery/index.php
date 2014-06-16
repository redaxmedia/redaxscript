<?php

/**
 * gallery loader start
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function gallery_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/gallery/styles/gallery.css';
	$loader_modules_styles[] = 'modules/gallery/styles/query.css';
	$loader_modules_scripts[] = 'modules/gallery/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/gallery/scripts/gallery.js';
}

/**
 * gallery
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $directory
 * @param array $options
 * @param string $command
 */

function gallery($directory = '', $options = '', $command = '')
{
	global $gallery_counter;

	/* define option variables */

	if (is_array($options))
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* else command fallback */

	else if ($options === 'build' || $options === 'delete')
	{
		$command = $options;
	}

	/* gallery directory object */

	$gallery_directory = New Redaxscript_Directory($directory, 'thumbs');
	$gallery_directory_array = $gallery_directory->get();

	/* reverse order */

	if ($option_order == 'desc')
	{
		$gallery_directory_array = array_reverse($gallery_directory_array);
	}

	/* delete gallery thumbs directory */

	if ($command == 'delete')
	{
		$gallery_directory->remove('thumbs');
	}

	/* else show gallery thumbs */

	else
	{
		/* collect gallery */

		$gallery_total = count($gallery_directory_array);
		$gallery_id = str_replace('/', '_', $directory) . '_' . ++$gallery_counter;
		if ($gallery_total)
		{
			foreach ($gallery_directory_array as $value)
			{
				$path = $directory . '/' . $value;
				$thumb_route = $directory . '/thumbs/' . $value;

				/* build thumb */

				if (file_exists($thumb_route) == '' || $command == 'build')
				{
					gallery_build_thumb($value, $directory, $options);
				}
				if (file_exists($thumb_route))
				{
					/* read exif data */

					$image_data = exif_read_data($path);
					if ($image_data)
					{
						$image_artist = $image_data['Artist'];
						$image_datetime = $image_data['DateTime'];
						if ($image_datetime)
						{
							$image_date = date(s('date'), strtotime($image_datetime));
						}
						else
						{
							$image_date = '';
						}
						$image_description = $image_data['ImageDescription'];
					}

					/* build data string */

					$data_string = 'data-counter="' . ++$image_counter . '" data-total="' . $gallery_total . '" data-id="' . $gallery_id . '"';
					if ($image_artist)
					{
						$data_string .= ' data-artist="' . $image_artist . '"';
					}
					if ($image_date)
					{
						$data_string .= ' data-date="' . $image_date . '"';
					}
					if ($image_description)
					{
						$data_string .= ' data-description="' . $image_description . '"';
						$alt_string = ' alt="' . $image_description . '"';
					}
					else
					{
						$alt_string = ' alt="' . str_replace('_', ' ', pathinfo($value, PATHINFO_FILENAME)) . '"';
					}

					/* collect image output */

					$image = '<img src="' . $thumb_route . '" class="image image_gallery"' . $alt_string . ' />';
					$output .= '<li class="item_gallery">' . anchor_element('', '', 'link_gallery', $image, $path, $image_description, $data_string) . '</li>';
				}
			}

			/* collect list output */

			if ($output)
			{
				$output = '<ul id="' . $gallery_id . '" class="js_list_gallery list_gallery ' . $gallery_id . ' clear_fix">' . $output . '</ul>';
				echo $output;
			}
		}

		/* delete gallery thumbs directory */

		else
		{
			$gallery_directory->remove('thumbs');
		}
	}
}

/**
 * gallery build thumb
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $input
 * @param string $directory
 * @param array $options
 */

function gallery_build_thumb($input = '', $directory = '', $options)
{
	/* define option variables */

	if (is_array($options))
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* fallback */

	if ($option_height == '')
	{
		$option_height = 100;
	}
	if ($option_quality == '')
	{
		$option_quality = 80;
	}

	/* get extension */

	$extension = strtolower(pathinfo($input, PATHINFO_EXTENSION));
	$path = $directory . '/' . $input;

	/* switch extension */

	switch ($extension)
	{
		case 'gif':
			$image = imagecreatefromgif($path);
		case 'jpg':
			$image = imagecreatefromjpeg($path);
			break;
		case 'png':
			$image = imagecreatefrompng($path);
			break;
	}

	/* original image dimensions */

	$original_dimensions = getimagesize($path);
	$original_height = $original_dimensions[1];
	$original_width = $original_dimensions[0];

	/* calculate image dimensions */

	if ($option_height)
	{
		$option_scaling = $option_height / $original_height * 100;
	}
	else
	{
		$option_height = round($option_scaling / 100 * $original_height);
	}
	$option_width = round($option_scaling / 100 * $original_width);

	/* create thumbs directory */

	$thumbs_directory = $directory . '/thumbs';
	if (is_dir($thumbs_directory) == '')
	{
		mkdir($thumbs_directory, 0755);
	}

	/* create thumbs */

	$output = $thumbs_directory . '/' . $input;
	$process = imagecreatetruecolor($option_width, $option_height);
	imagecopyresampled($process, $image, 0, 0, 0, 0, $option_width, $option_height, $original_width, $original_height);
	imagejpeg($process, $output, $option_quality);

	/* destroy images */

	imagedestroy($image);
	imagedestroy($process);
}


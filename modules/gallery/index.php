<?php

/**
 * gallery loader start
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
 * gallery loader scripts transport start
 */

function gallery_loader_scripts_transport_start()
{
	$output = languages_transport(array(
		'gallery_image_artist',
		'gallery_image_description',
		'gallery_image_next',
		'gallery_image_previous',
		'gallery_divider'
	));
	echo $output;
}

/**
 * gallery
 *
 * @param string $directory
 * @param integer $quality
 * @param integer $scaling
 * @param integer $height
 * @param string $command
 */

function gallery($directory = '', $quality = '', $scaling = '', $height = '', $command = '')
{
	/* define variables */

	if (is_numeric($quality) == '')
	{
		$quality = 80;
		if ($command == '')
		{
			$command = $quality;
		}
	}
	else if ($quality > 100)
	{
		$quality = 100;
	}
	if (is_numeric($scaling) == '')
	{
		$scaling = 20;
		if ($command == '')
		{
			$command = $scaling;
		}
	}
	else if ($scaling > 100)
	{
		$scaling = 100;
	}
	if (is_numeric($height) == '')
	{
		$height = 0;
		if ($command == '')
		{
			$command = $height;
		}
	}

	/* gallery directory object */

	$gallery_directory = New Redaxscript_Directory($directory, 'thumbs');
	$gallery_directory_array = $gallery_directory->getOutput();

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
		$gallery_name = str_replace('/', '_', $directory);
		if ($gallery_total)
		{
			foreach ($gallery_directory_array as $value)
			{
				$route = $directory . '/' . $value;
				$thumb_route = $directory . '/thumbs/' . $value;

				/* build gallery thumb */

				if (file_exists($thumb_route) == '' || $command == 'build')
				{
					gallery_build_thumb($value, $directory, $route, $quality, $scaling, $height);
				}
				if (file_exists($thumb_route))
				{
					/* read exif data */

					$image_data = exif_read_data($route);
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

					$data_string = 'data-counter="' . ++$gallery_counter . '" data-total="' . $gallery_total . '" data-gallery-name="' . $gallery_name . '"';
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
					}

					/* collect image output */

					$image = '<img src="' . $thumb_route . '" class="image image_gallery" alt="' . $image_description . '" ' . $data_string . ' />';
					$output .= '<li class="item_gallery">' . anchor_element('', '', 'link_gallery', $image, $route, $image_description, 'rel="nofollow"') . '</li>';
				}
			}

			/* collect list output */

			if ($output)
			{
				$output = '<ul id="' . $gallery_name . '" class="js_list_gallery list_gallery clear_fix">' . $output . '</ul>';
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
 * @param string $input
 * @param string $directory
 * @param string $route
 * @param integer $quality
 * @param integer $scaling
 * @param integer $height
 */

function gallery_build_thumb($input = '', $directory = '', $route = '', $quality = '', $scaling = '', $height = '')
{
	$extension = strtolower(pathinfo($input, PATHINFO_EXTENSION));

	/* switch extension */

	switch ($extension)
	{
		case 'gif':
			$image = imagecreatefromgif($route);
		case 'jpg':
			$image = imagecreatefromjpeg($route);
			break;
		case 'png':
			$image = imagecreatefrompng($route);
			break;
	}

	/* calculate image dimensions */

	$original_size = getimagesize($route);
	if ($height)
	{
		$scaling = $height / $original_size[1] * 100;
	}
	else
	{
		$height = round($scaling / 100 * $original_size[1]);
	}
	$width = round($scaling / 100 * $original_size[0]);

	/* create thumbs directory */

	$thumbs_directory = $directory . '/thumbs';
	if (is_dir($thumbs_directory) == '')
	{
		mkdir($thumbs_directory, 0755);
	}

	/* create thumbs */

	$output = $thumbs_directory . '/' . $input;
	$process = imagecreatetruecolor($width, $height);
	imagecopyresampled($process, $image, 0, 0, 0, 0, $width, $height, $original_size[0], $original_size[1]);
	imagejpeg($process, $output, $quality);
	imagedestroy($image);
	imagedestroy($process);
}
?>
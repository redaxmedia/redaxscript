<?php

/* gallery loader start */

function gallery_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/gallery/styles/gallery.css';
	$loader_modules_scripts[] = 'modules/gallery/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/gallery/scripts/gallery.js';
}

/* gallery loader scripts transport start */

function gallery_loader_scripts_transport_start()
{
	$output = languages_transport(array(
		'gallery_image_next',
		'gallery_image_previous'
	));
	echo $output;
}

/* gallery */

function gallery($directory = '', $quality = '', $scaling = '', $max_height = '', $command = '')
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
	if (is_numeric($max_height) == '')
	{
		$max_height = 0;
		if ($command == '')
		{
			$command = $max_height;
		}
	}

	/* delete gallery thumbs directory */

	if ($command == 'delete')
	{
		remove_directory($directory . '/thumbs', 1);
	}

	/* else show gallery thumbs */

	else
	{
		$gallery_directory = read_directory($directory, 'thumbs');
		if (count($gallery_directory))
		{
			foreach ($gallery_directory as $value)
			{
				$string = $directory . '/' . $value;
				$thumb_string = $directory . '/thumbs/' . $value;

				/* build gallery thumb */

				if (file_exists($thumb_string) == '' || $command == 'build')
				{
					gallery_build_thumb($value, $directory, $string, $quality, $scaling, $max_height);
				}
				if (file_exists($thumb_string))
				{
					$output .= '<li class="item_gallery">' . anchor_element('', '', 'link_gallery', '<img src="' . $thumb_string . '" class="image image_gallery" alt="' . $value . '" />', $string, $value, 'rel="nofollow"') . '</li>';
				}
			}
			if ($output)
			{
				$id = str_replace('/', '_', $directory);
				$output = '<ul id="' . $id . '" class="js_gallery list_gallery clear_fix">' . $output . '</ul>';
				echo $output;
			}
		}

		/* delete gallery thumbs directory */

		else
		{
			remove_directory($directory . '/thumbs', 1);
		}
	}
}

/* gallery build thumb */

function gallery_build_thumb($input = '', $directory = '', $string = '', $quality = '', $scaling = '', $max_height = '')
{
	$extension = strtolower(pathinfo($input, PATHINFO_EXTENSION));

	/* switch extension */

	switch ($extension)
	{
		case 'gif':
			$image = imagecreatefromgif($string);
		case 'jpg':
			$image = imagecreatefromjpeg($string);
			break;
		case 'png':
			$image = imagecreatefrompng($string);
			break;
	}

	/* calculate image dimensions */

	$original_size = getimagesize($string);
	if ($max_height)
	{
		$scaling = $max_height / $original_size[1] * 100;
	}
	$height = round($scaling / 100 * $original_size[1]);
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
<?php

/**
 * file manager loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function file_manager_loader_start()
{
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin' && SECOND_PARAMETER == 'file-manager')
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/file_manager/styles/file_manager.css';
		$loader_modules_scripts[] = 'modules/file_manager/scripts/init.js';
		$loader_modules_scripts[] = 'modules/file_manager/scripts/file_manager.js';
	}
}

/**
 * file manager render start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function file_manager_render_start()
{
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin' && SECOND_PARAMETER == 'file-manager')
	{
		Redaxscript\Registry::set('title', l('file_manager', '_file_manager'));
		Redaxscript\Registry::set('centerBreak', true);
	}
}

/**
 * file manager center start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function file_manager_center_start()
{
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin' && SECOND_PARAMETER == 'file-manager')
	{
		if (THIRD_PARAMETER == 'upload')
		{
			file_manager_upload(FILE_MANAGER_DIRECTORY);
		}
		else if (THIRD_PARAMETER == 'delete')
		{
			if (TOKEN_PARAMETER == '')
			{
				$error = l('token_incorrect');
			}
			else
			{
				/* file manager directory object */

				$file_manager_directory = new Redaxscript\Directory();
				$file_manager_directory->init(FILE_MANAGER_DIRECTORY);
				$file_manager_directory_string = $file_manager_directory->getArray();

				/* remove related children */

				$file_manager_directory->remove($file_manager_directory_string);
			}
		}

		/* handle error */

		if ($error)
		{
			notification(l('error_occurred'), $error, l('back'), 'admin/file-manager');
		}

		/* handle success */

		else
		{
			file_manager(FILE_MANAGER_DIRECTORY);
		}
	}
}

/**
 * file manager admin panel panel list modules
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @return string
 */

function file_manager_admin_panel_list_modules()
{
	$output = '<li>' . anchor_element('internal', '', '', l('file_manager', '_file_manager'), 'admin/file-manager') . '</li>';
	return $output;
}

/**
 * file manager
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $directory
 */

function file_manager($directory = '')
{
	if (!is_dir($directory))
	{
		mkdir($directory, 0777);
	}
	if (!is_dir($directory))
	{
		$output = '<div class="rs-box-note rs-note-error">' . l('directory_create', '_file_manager') . l('colon') . ' ' . $directory . l('point') . '</div>';
	}
	else if (!is_writable($directory))
	{
		$output = '<div class="rs-box-note rs-note-error">' . l('directory_permission_grant', '_file_manager') . l('colon') . ' ' . $directory . l('point') . '</div>';
	}

	/* collect listing output */

	$output .= '<h2 class="rs-title-content">' . l('file_manager', '_file_manager') . '</h2>';
	$output .= form_element('form', 'form_file_manager', 'rs-js-form-file-manager rs-form-file-manager', '', '', '', 'action="' . REWRITE_ROUTE . 'admin/file-manager/upload" method="post" enctype="multipart/form-data"');
	$output .= form_element('file', '', 'rs-js-file rs-field-file rs-hide-if-js', 'file', '', l('browse', '_file_manager'));
	$output .= '<button type="submit" class="rs-js-upload rs-field-upload rs-admin-button rs-hide-if-js">' . l('upload', '_file_manager') . '</span></span></button>';
	$output .= '</form>';
	$output .= '<div class="rs-admin-wrapper-table"><table class="rs-table rs-admin-table">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-s4o6 rs-column-first">' . l('name') . '</th><th class="rs-s1o6 rs-column-second">' . l('file_size', '_file_manager') . '</th><th class="rs-s1o6 rs-column-last">' . l('date') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="rs-column-first">' . l('name') . '</td><td class="rs-column-second">' . l('file_size', '_file_manager') . '</td><td class="rs-column-last">' . l('date') . '</td></tr></tfoot>';

	/* file manager directory object */

	$file_manager_directory = new Redaxscript\Directory();
	$file_manager_directory->init($directory);
	$file_manager_directory_array = $file_manager_directory->getArray();

	/* collect directory output */

	if (count($file_manager_directory_array))
	{
		$output .= '<tbody>';
		foreach ($file_manager_directory_array as $key => $value)
		{
			$output .= '<tr><td class="rs-column-first">';
			$path = $directory . '/' . $value;
			if (function_exists('exif_imagetype') && exif_imagetype($path))
			{
				$output .= anchor_element('external', '', '', $value, ROOT . '/' . $path);
			}
			else
			{
				$output .= $value;
			}

			/* collect control output */

			$output .= '<ul class="rs-admin-list-control"><li class="rs-item-delete">' . anchor_element('internal', '', 'rs-js-confirm', l('delete'), 'admin/file-manager/delete/' . $key . '/' . TOKEN) . '</li></ul>';

			/* collect filesize and filetime output */

			$output .= '</td><td class="rs-column-second">' . ceil(filesize($path) / 1024) . ' Kb</td><td class="rs-column-last">' . date(s('date'), filectime($path)) . '</td></tr>';
		}
		$output .= '</tbody>';
	}
	else
	{
		$error = l('file_no', '_file_manager') . l('point');
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="2">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	echo $output;
}

/**
 * file manager clean file name
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function file_manager_clean_file_name($input = '')
{
	$output = trim(strtolower($input));
	$output = preg_replace('/[-|\s+]/i', '_', $output);
	$output = preg_replace('/[^a-z0-9._]/i', '', $output);
	return $output;
}

/**
 * file manager upload
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $directory
 */

function file_manager_upload($directory = '')
{
	$file = $_FILES['file']['tmp_name'];
	$file_name = file_manager_clean_file_name($_FILES['file']['name']);
	$file_size = $_FILES['file']['size'];

	/* validate post */

	if (function_exists('exif_imagetype'))
	{
		if (exif_imagetype($file) == '')
		{
			$error = l('file_type_limit', '_file_manager') . l('point');
		}
	}

	/* filesize limit */

	if ($file_size > 1048576)
	{
		$error = l('file_size_limit', '_file_manager') . l('point');
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="rs-box-note rs-note-warning">' . $error . '</div>';
		echo $output;
	}
	else
	{
		move_uploaded_file($file, $directory . '/' . $file_name);
	}
}


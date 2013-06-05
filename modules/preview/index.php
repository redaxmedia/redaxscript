<?php

/**
 * preview loader start
 */

function preview_loader_start()
{
	if (FIRST_PARAMETER == 'preview')
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/preview/styles/preview.css';
	}
}

/**
 * preview render start
 */

function preview_render_start()
{
	if (FIRST_PARAMETER == 'preview')
	{
		define('CENTER_BREAK', 1);
		define('TITLE', l('preview_preview'));
	}
}

/**
 * preview center start
 */

function preview_center_start()
{
	if (FIRST_PARAMETER == 'preview')
	{
		/* collect partial output */

		$output = '<div class="preview clear_fix">' . PHP_EOL;
		ob_start();
		include_once('modules/preview/partials/grid.phtml');
		include_once('modules/preview/partials/typography.phtml');
		include_once('modules/preview/partials/box.phtml');
		include_once('modules/preview/partials/form.phtml');
		include_once('modules/preview/partials/icon.phtml');
		include_once('modules/preview/partials/media.phtml');
		include_once('modules/preview/partials/interface.phtml');
		include_once('modules/preview/partials/table.phtml');
		include_once('modules/preview/partials/dialog.phtml');
		include_once('modules/preview/partials/note.phtml');
		$output .= ob_get_clean() . PHP_EOL;
		$output .= '</div>';
		echo $output;
	}
}
?>
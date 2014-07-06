<?php

/**
 * preview loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
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
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function preview_render_start()
{
	if (FIRST_PARAMETER == 'preview')
	{
		define('CENTER_BREAK', 1);
		define('TITLE', l('preview', 'preview'));

		/* registry object */

		$registry = Redaxscript_Registry::getInstance();
		$registry->set('title', l('preview', 'preview'));
	}
}

/**
 * preview center start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function preview_center_start()
{
	if (FIRST_PARAMETER == 'preview')
	{
		/* collect partial output */

		$output = '<div class="preview clear_fix">' . PHP_EOL;
		ob_start();

		/* include single file */

		if (file_exists('modules/preview/partials/' . SECOND_PARAMETER . '.phtml'))
		{
			include_once('modules/preview/partials/' . SECOND_PARAMETER . '.phtml');
		}

		/* else include all */

		else
		{
			include_once('modules/preview/partials/grid.phtml');
			include_once('modules/preview/partials/typography.phtml');
			include_once('modules/preview/partials/box.phtml');
			include_once('modules/preview/partials/form.phtml');
			include_once('modules/preview/partials/form_admin.phtml');
			include_once('modules/preview/partials/icon.phtml');
			include_once('modules/preview/partials/media.phtml');
			include_once('modules/preview/partials/interface.phtml');
			include_once('modules/preview/partials/accordion.phtml');
			include_once('modules/preview/partials/accordion_admin.phtml');
			include_once('modules/preview/partials/tab.phtml');
			include_once('modules/preview/partials/tab_admin.phtml');
			include_once('modules/preview/partials/table.phtml');
			include_once('modules/preview/partials/table_admin.phtml');
			include_once('modules/preview/partials/dialog.phtml');
			include_once('modules/preview/partials/dialog_admin.phtml');
			include_once('modules/preview/partials/note.phtml');
		}
		$output .= ob_get_clean() . PHP_EOL;
		$output .= '</div>';
		echo $output;
	}
}


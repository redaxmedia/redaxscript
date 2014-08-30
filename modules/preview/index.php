<?php
namespace Redaxscript\Modules;

use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * preview template elements
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Preview extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'Preview',
		'alias' => 'preview',
		'author' => 'Redaxmedia',
		'description' => 'Preview template elements',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_styles;
		$loader_modules_styles[] = 'modules/preview/styles/preview.css';
	}

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('firstParameter') === 'preview')
		{
			Registry::set('title', l('preview', 'preview'));
			Registry::set('centerBreak', 1);
		}
	}

	/**
	 * centerStart
	 *
	 * @since 2.2.0
	 */

	public static function centerStart()
	{
		if (Registry::get('firstParameter') === 'preview')
		{
			/* collect partial output */

			$output = '<div class="preview clear_fix">' . PHP_EOL;
			ob_start();

			/* include as needed */

			if (file_exists('modules/preview/partials/' . Registry::get('secondParameter') . '.phtml'))
			{
				include_once('modules/preview/partials/' . Registry::get('secondParameter') . '.phtml');
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
			$output .= '</div>' . PHP_EOL;
			echo $output;
		}
	}
}
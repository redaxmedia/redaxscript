<?php
namespace Redaxscript\Modules\GetFile;

use Redaxscript\Db;
use Redaxscript\Module;

/**
 * file information helper
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class GetFile extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Get file',
		'alias' => 'GetFile',
		'author' => 'Redaxmedia',
		'description' => 'File information helper',
		'version' => '2.4.0'
	);

	/**
	 * render
	 *
	 * @since 2.2.0
	 *
	 * @param string $fileName
	 * @param string $type
	 * @param string $unit
	 *
	 * @return string
	 */

	public static function render($fileName = null, $type = 'size', $unit = 'kb')
	{
		$output = '';

		/* size */

		if ($type === 'size')
		{
			$output = filesize($fileName);

			/* calculate output */

			if ($unit === 'kb' || $unit === 'mb')
			{
				$output = $output / 1024;
			}
			if ($unit === 'mb')
			{
				$output = $output / 1024;
			}
			$output = ceil($output);
		}

		/* date */

		else if ($type === 'date')
		{
			$output = date(Db::getSettings('date'), filectime($fileName));
		}
		return $output;
	}
}

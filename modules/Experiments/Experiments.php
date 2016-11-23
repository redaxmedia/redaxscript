<?php
namespace Redaxscript\Modules\Experiments;

use Redaxscript\Head;
use Redaxscript\Registry;

/**
 * integrate google experiments
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Experiments extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Experiments',
		'alias' => 'Experiments',
		'author' => 'Redaxmedia',
		'description' => 'Integrate Google Experiments',
		'version' => '3.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public static function renderStart()
	{
		if (Registry::get('loggedIn') !== Registry::get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('//google-analytics.com/cx/api.js?experiment=' . self::$_configArray['id'])
				->appendFile('modules/Experiments/assets/scripts/init.js')
				->appendFile('modules/Experiments/assets/scripts/experiments.js');
		}
	}
}

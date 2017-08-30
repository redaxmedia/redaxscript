<?php
namespace Redaxscript\Modules\Experiments;

use Redaxscript\Head;

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
		'version' => '3.2.3'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('loggedIn') !== $this->_registry->get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('https://google-analytics.com/cx/api.js?experiment=' . $this->_configArray['id'])
				->appendFile('modules/Experiments/assets/scripts/init.js')
				->appendFile('modules/Experiments/dist/scripts/experiments.min.js');
		}
	}
}

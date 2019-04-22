<?php
namespace Redaxscript\Modules\Experiments;

use Redaxscript\Head;
use Redaxscript\Module;
use function http_build_query;

/**
 * integrate google experiments
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Experiments extends Module\Module
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
		'version' => '4.0.0'
	];

	/**
	 * array of the option
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'id' => '0000000000000000000000'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('loggedIn') !== $this->_registry->get('token'))
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					'https://google-analytics.com/cx/api.js?' . http_build_query(
					[
						'experiment' => $this->_optionArray['id']
					]),
					'modules/Experiments/assets/scripts/init.js',
					'modules/Experiments/dist/scripts/experiments.min.js'
				]);
		}
	}
}

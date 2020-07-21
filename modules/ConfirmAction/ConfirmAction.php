<?php
namespace Redaxscript\Modules\ConfirmAction;

use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Modules;

/**
 * confirm critical action
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class ConfirmAction extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Confirm Action',
		'alias' => 'ConfirmAction',
		'author' => 'Redaxmedia',
		'description' => 'Implement a confirmation for critical action',
		'version' => '4.3.2'
	];

	/**
	 * renderStart
	 *
	 * @since 4.3.0
	 */

	public function renderStart() : void
	{
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'modules/ConfirmAction/assets/scripts/init.js',
				'modules/ConfirmAction/dist/scripts/confirm-action.min.js'
			]);
	}

	/**
	 * install the module
	 *
	 * @since 4.3.0
	 *
	 * @return bool
	 */

	public function install() : bool
	{
		$dialog = new Modules\Dialog\Dialog($this->_registry, $this->_request, $this->_language, $this->_config);
		return $dialog->reinstall() && parent::install();
	}
}

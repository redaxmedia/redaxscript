<?php
namespace Redaxscript\Modules\TestDummy;

use Redaxscript\Module;

/**
 * test dummy
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class TestDummy extends Module\Notification
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Test Dummy',
		'alias' => 'TestDummy',
		'author' => 'Redaxmedia',
		'description' => 'Test Dummy',
		'version' => '3.3.2'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.1.0
	 *
	 * @return array|bool
	 */

	public function adminPanelNotification()
	{
		$this->setNotification('info', 'Test Dummy');
		return $this->getNotification();
	}

	/**
	 * render
	 *
	 * @since 3.1.0
	 *
	 * @param int $firstNumber
	 * @param int $secondNumber
	 *
	 * @return int
	 */

	public function render(int $firstNumber = 1, int $secondNumber = 1) : int
	{
		return $firstNumber + $secondNumber;
	}
}

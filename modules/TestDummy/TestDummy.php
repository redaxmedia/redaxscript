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

class TestDummy extends Module\Module
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
		'version' => '3.2.2'
	];

	/**
	 * adminPanelNotification
	 *
	 * @since 3.1.0
	 *
	 * @return array
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
	 * @param integer $firstNumber
	 * @param integer $secondNumber
	 *
	 * @return integer
	 */

	public function render($firstNumber = 1, $secondNumber = 1)
	{
		return $firstNumber + $secondNumber;
	}
}

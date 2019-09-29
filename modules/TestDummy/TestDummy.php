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

class TestDummy extends Module\Metadata
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
		'version' => '0.0.0'
	];

	/**
	 * adminDashboard
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */

	public function adminDashboard() : array
	{
		$this->setDashboard('Test Dummy', 2);
		return $this->getDashboardArray();
	}

	/**
	 * adminNotification
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */

	public function adminNotification() : array
	{
		$this->setNotification('success',
		[
			'text' => 'Success',
			'attr' =>
			[
				'href' => 'http://localhost',
				'target' => '_blank'
			]
		]);
		$this->setNotification('warning', 'Warning');
		$this->setNotification('error', 'Error');
		$this->setNotification('info', 'Info');
		return $this->getNotificationArray();
	}

	/**
	 * render
	 *
	 * @since 3.1.0
	 *
	 * @param int $firstNumber
	 * @param int $secondNumber
	 *
	 * @return string
	 */

	public function render(int $firstNumber = 1, int $secondNumber = 1) : string
	{
		return $this->_language->get('_number')[$firstNumber + $secondNumber];
	}
}

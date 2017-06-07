<?php
namespace Redaxscript\Template\Helper;

/**
 * helper class to provide a client helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Client extends HelperAbstract
{
	/**
	 * array of devices
	 *
	 * @var array
	 */

	protected $_deviceArray =
	[
		'mobile' => 'myMobile',
		'tablet' => 'myTablet',
		'desktop' => 'myDesktop'
	];

	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function process()
	{
		$browserArray = $this->_getBrowserArray();
		$deviceArray = $this->_getDeviceArray();
		if (is_array($browserArray) && is_array($deviceArray))
		{
			$clientArray = array_unique(array_merge(
				$browserArray,
				$deviceArray
			));
		}
		return is_array($clientArray) ? $clientArray : [];
	}

	/**
	 * get the browser array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _getBrowserArray()
	{
		return
		[
			$this->_registry->get('myBrowser'),
			$this->_registry->get('myBrowserVersion'),
			$this->_registry->get('myEngine')
		];
	}

	/**
	 * get the device array
	 *
	 * @since 3.0.0
	 *
	 * @return array|boolean
	 */

	protected function _getDeviceArray()
	{
		foreach ($this->_deviceArray as $system => $value)
		{
			$device = $this->_registry->get($value);
			if ($device)
			{
				return
				[
					$system,
					$device
				];
			}
		}
		return false;
	}
}

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
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @param string $prefix
	 *
	 * @return string
	 */

	public function process(string $prefix = null)
	{
		$clientArray = array_unique(array_merge(
			$this->_getBrowserArray(),
			$this->_getDeviceArray()
		));

		/* process client */

		foreach ($clientArray as $key => $value)
		{
			$clientArray[$key] = $prefix . $value;
		}
		return implode(' ', $clientArray);
	}

	/**
	 * get the browser array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _getBrowserArray() : array
	{
		return array_filter(
		[
			$this->_registry->get('myBrowser'),
			$this->_registry->get('myBrowserVersion'),
			$this->_registry->get('myEngine')
		]);
	}

	/**
	 * get the device array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _getDeviceArray() : array
	{
		$myMobile = $this->_registry->get('myMobile');
		$myTablet = $this->_registry->get('myTablet');
		$myDesktop = $this->_registry->get('myDesktop');
		if ($myMobile)
		{
			return
			[
				'mobile',
				$myMobile
			];
		}
		if ($myTablet)
		{
			return
			[
				'tablet',
				$myTablet
			];
		}
		if ($myDesktop)
		{
			return
			[
				'desktop',
				$myDesktop
			];
		}
		return [];
	}
}

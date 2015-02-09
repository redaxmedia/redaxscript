<?php
namespace Redaxscript\Client;

/**
 * children class to detect mobile device
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

class Mobile extends Client
{
	/**
	 * init the class
	 *
	 * @since 2.4.0
	 */

	public function init()
	{
		$this->_detect(array(
			'mobile',
			'android',
			'blackberry',
			'iphone',
			'ipod',
			'palm'
		));
	}
}
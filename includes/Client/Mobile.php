<?php
namespace Redaxscript\Client;

/**
 * children class to detect the mobile device
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

class Mobile extends ClientAbstract
{
	/**
	 * automate run
	 *
	 * @since 2.4.0
	 */

	public function autorun()
	{
		$this->_detect(
		[
			'mobile',
			'android',
			'blackberry',
			'iphone',
			'ipod',
			'palm'
		]);
	}
}

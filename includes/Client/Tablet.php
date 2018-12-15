<?php
namespace Redaxscript\Client;

/**
 * children class to detect the tablet device
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

class Tablet extends ClientAbstract
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
			'tablet',
			'android',
			'ipad',
			'kindle',
			'xoom'
		]);
	}
}

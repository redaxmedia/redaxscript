<?php
namespace Redaxscript\Client;

/**
 * children class to detect tablet device
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

	protected function _autorun()
	{
		$this->_detect(array(
			'tablet',
			'android',
			'ipad',
			'kindle',
			'xoom'
		));
	}
}

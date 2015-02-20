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

class Tablet extends Client
{
	/**
	 * init the class
	 *
	 * @since 2.4.0
	 */

	public function init()
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
<?php
namespace Redaxscript\Client;

/**
 * children class to detect browser engine
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

class Engine extends Client
{
	/**
	 * init the class
	 *
	 * @since 2.4.0
	 */

	public function init()
	{
		$this->_detect(array(
			'gecko',
			'presto',
			'trident',
			'webkit'
		));
	}
}
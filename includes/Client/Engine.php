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

class Engine extends ClientAbstract
{
	/**
	 * automate run
	 *
	 * @since 2.4.0
	 */

	protected function _autorun()
	{
		$this->_detect(
		[
			'gecko',
			'presto',
			'trident',
			'webkit',
			'edge'
		]);
	}
}

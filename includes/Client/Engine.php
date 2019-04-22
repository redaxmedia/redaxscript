<?php
namespace Redaxscript\Client;

/**
 * children class to detect the browser engine
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

	public function autorun() : void
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

<?php
namespace Redaxscript\Client;

/**
 * children class to detect desktop device
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

class Desktop extends ClientAbstract
{
	/**
	 * init the class
	 *
	 * @since 2.4.0
	 */

	public function init()
	{
		$this->_detect(array(
			'bsd',
			'linux',
			'macintosh',
			'solaris',
			'windows'
		));
	}
}
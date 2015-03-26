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
	 * automate run
	 *
	 * @since 2.4.0
	 */

	protected function _autorun()
	{
		$this->_detect(array(
			'bsd',
			'linux',
			'ubuntu',
			'macintosh',
			'solaris',
			'windows'
		));
	}
}

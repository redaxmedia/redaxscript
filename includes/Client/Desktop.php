<?php
namespace Redaxscript\Client;

/**
 * children class to detect the desktop device
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

	public function autorun()
	{
		$this->_detect(
		[
			'bsd',
			'linux',
			'ubuntu',
			'macintosh',
			'solaris',
			'windows'
		]);
	}
}

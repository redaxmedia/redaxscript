<?php
namespace Redaxscript\Client;

/**
 * children class to detect the browser version
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

class Version extends ClientAbstract
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
			'version',
			'chrome',
			'edge',
			'firefox',
			'konqueror',
			'msie',
			'netscape'
		], 'version');
	}
}

<?php
namespace Redaxscript\Client;

/**
 * children class to detect browser version
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

	protected function _autorun()
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

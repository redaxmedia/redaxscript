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
	 * init the class
	 *
	 * @since 2.4.0
	 */

	public function init()
	{
		$this->_detect(array(
			'version',
			'chrome',
			'firefox',
			'konqueror',
			'msie',
			'netscape',
		), 'version');
	}
}
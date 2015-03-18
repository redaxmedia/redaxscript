<?php
namespace Redaxscript\Client;

/**
 * children class to detect browser name
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

class Browser extends ClientAbstract
{
	/**
	 * init the class
	 *
	 * @since 2.4.0
	 */

	public function init()
	{
		$this->_detect(array(
			'safari',
			'chrome',
			'chromium',
			'firefox',
			'konqueror',
			'msie',
			'netscape',
			'opera'
		));
	}
}
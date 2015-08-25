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
	 * automate run
	 *
	 * @since 2.4.0
	 */

	protected function _autorun()
	{
		$this->_detect(array(
			'safari',
			'chrome',
			'chromium',
			'edge',
			'firefox',
			'konqueror',
			'msie',
			'netscape',
			'opera'
		));
	}
}

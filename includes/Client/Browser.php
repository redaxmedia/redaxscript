<?php
namespace Redaxscript\Client;

/**
 * children class to detect the browser name
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

	public function autorun()
	{
		$this->_detect(
		[
			'safari',
			'chrome',
			'chromium',
			'edge',
			'firefox',
			'konqueror',
			'msie',
			'netscape',
			'opera'
		]);
	}
}

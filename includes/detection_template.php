<?php

/**
 * Redaxscript_DetectionTemplate
 *
 * @since 2.0.0
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Detection_Template extends Redaxscript_Detection
{
	/**
	 * init
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		$this->_detect(array(
			'parameter' => $this->_getParameter('t'),
			'session' => isset($_SESSION[ROOT . '/template']) ? $_SESSION[ROOT . '/template'] : '',
			'contents' => retrieve('template', LAST_TABLE, 'id', LAST_ID),
			'settings' => s('template'),
			'fallback' => 'default'
		), 'template', 'templates/{type}/index.phtml');
	}
}
?>
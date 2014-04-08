<?php

/**
 * children class to detect the required template
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
	 * init the class
	 *
	 * @since 2.1.0
	 */

	public function init()
	{
		$this->_detect(array(
			'parameter' => $this->_getParameter('t'),
			'session' => isset($_SESSION[$this->_registry->get('root') . '/template']) ? $_SESSION[$this->_registry->get('root') . '/template'] : '',
			'contents' => retrieve('template', $this->_registry->get('lastTable'), 'id', $this->_registry->get('lastId')),
			'settings' => s('template'),
			'fallback' => 'default'
		), 'template', 'templates/{value}/index.phtml');
	}
}
?>

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
			'query' => Redaxscript\Request::getQuery('t'),
			'session' => Redaxscript\Request::getSession($this->_registry->get('root') . '/template'),
			'contents' => $this->_registry->get('lastTable') ? Redaxscript\Db::forPrefixTable($this->_registry->get('lastTable'))->where('id', $this->_registry->get('lastId'))->findOne()->template : null,
			'settings' => $this->_registry->get('dbConnected') ? Redaxscript\Db::getSettings('template') : null,
			'fallback' => 'default'
		), 'template', 'templates/{value}/index.phtml');
	}
}
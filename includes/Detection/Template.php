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
			'query' => Redaxscript_Request::getQuery('t'),
			'session' => Redaxscript_Request::getSession($this->_registry->get('root') . '/template'),
			'contents' => $this->_registry->get('lastTable') ? Redaxscript_Db::forPrefixTable($this->_registry->get('lastTable'))->where('id', $this->_registry->get('lastId'))->findOne()->template : null,
			'settings' => Redaxscript_Db::getSettings('template'),
			'fallback' => 'default'
		), 'template', 'templates/{value}/index.phtml');
	}
}
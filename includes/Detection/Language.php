<?php

/**
 * children class to detect the required language
 *
 * @since 2.0.0
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Detection_Language extends Redaxscript_Detection
{
	/**
	 * init the class
	 *
	 * @since 2.1.0
	 */

	public function init()
	{
		$this->_detect(array(
			'query' => Redaxscript_Request::getQuery('l'),
			'session' => Redaxscript_Request::getSession($this->_registry->get('root') . '/language'),
			'contents' => $this->_registry->get('lastTable') ? Redaxscript_Db::forPrefixTable($this->_registry->get('lastTable'))->where('id', $this->_registry->get('lastId'))->findOne()->language : null,
			'settings' => Redaxscript_Db::getSettings('language'),
			'browser' => substr(Redaxscript_Request::getServer('HTTP_ACCEPT_LANGUAGE'), 0, 2),
			'fallback' => 'en'
		), 'language', 'languages/{value}.json');
    }
}

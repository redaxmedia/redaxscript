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
			//'contents' => retrieve('language', $this->_registry->get('lastTable'), 'id', $this->_registry->get('lastId')),
			//'settings' => s('language') === 'detect' ? '' : s('language'),
			'browser' => substr(Redaxscript_Request::getServer('HTTP_ACCEPT_LANGUAGE'), 0, 2),
			'fallback' => 'en'
		), 'language', 'languages/{value}.json');
    }
}

<?php
namespace Redaxscript\Detection;
use Redaxscript\Db;
use Redaxscript\Detection;
use Redaxscript\Request;

/**
 * children class to detect the required language
 *
 * @since 2.0.0
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Language extends Detection
{
	/**
	 * init the class
	 *
	 * @since 2.1.0
	 */

	public function init()
	{
		$this->_detect(array(
			'query' => Request::getQuery('l'),
			'session' => Request::getSession($this->_registry->get('root') . '/language'),
			'contents' => $this->_registry->get('lastTable') ? Db::forPrefixTable($this->_registry->get('lastTable'))->where('id', $this->_registry->get('lastId'))->findOne()->language : null,
			'settings' => Db::getSettings('language'),
			'browser' => substr(Request::getServer('HTTP_ACCEPT_LANGUAGE'), 0, 2),
			'fallback' => 'en'
		), 'language', 'languages/{value}.json');
    }
}

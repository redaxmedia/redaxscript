<?php
namespace Redaxscript\Detector;

use Redaxscript\Db;
use Redaxscript\Request;

/**
 * children class to detect the required language
 *
 * @since 2.0.0
 *
 * @category Detector
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Language extends Detector
{
	/**
	 * init the class
	 *
	 * @since 2.1.0
	 */

	public function init()
	{
		$root = $this->_registry->get('root');
		$dbStatus = $this->_registry->get('dbStatus');
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');

		/* detect language */

		$this->_detect(array(
			'query' => Request::getQuery('l'),
			'session' => Request::getSession($root . '/language'),
			'contents' => $lastTable ? Db::forTablePrefix($lastTable)->where('id', $lastId)->findOne()->language : null,
			'settings' => $dbStatus === 2 ? Db::getSettings('language') : null,
			'browser' => substr(Request::getServer('HTTP_ACCEPT_LANGUAGE'), 0, 2),
			'fallback' => 'en'
		), 'language', 'languages/{value}.json');
	}
}

<?php
namespace Redaxscript\Detector;

use Redaxscript\Db;
use Redaxscript\Request;

/**
 * children class to detect the required template
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Detector
 * @author Henry Ruhs
 */

class Template extends DetectorAbstract
{
	/**
	 * automate run
	 *
	 * @since 2.1.0
	 */

	protected function _autorun()
	{
		$dbStatus = $this->_registry->get('dbStatus');
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');

		/* detect template */

		$this->_detect(
		[
			'query' => $this->_request->getQuery('t'),
			'session' => $this->_request->getSession('template'),
			'contents' => $lastTable ? Db::forTablePrefix($lastTable)->whereIdIs($lastId)->findOne()->template : null,
			'settings' => $dbStatus === 2 ? Db::getSetting('template') : null,
			'fallback' => 'default'
		], 'template', 'templates/' . $this->_filePlaceholder . '/index.phtml');
	}
}

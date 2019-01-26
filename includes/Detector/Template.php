<?php
namespace Redaxscript\Detector;

use Redaxscript\Model;

/**
 * children class to detect the current template
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

	public function autorun()
	{
		$settingModel = new Model\Setting();
		$contentModel = new Model\Content();
		$dbStatus = $this->_registry->get('dbStatus');
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');
		$path = 'templates' . DIRECTORY_SEPARATOR . $this->_filePlaceholder . DIRECTORY_SEPARATOR . 'index.phtml';
		$setupArray =
		[
			'query' => $this->_request->getQuery('t'),
			'session' => $this->_request->getSession('template'),
			'contents' => $contentModel->getByTableAndId($lastTable, $lastId)->template,
			'settings' => $dbStatus === 2 ? $settingModel->get('template') : null,
			'fallback' => 'default'
		];

		/* detect template */

		$this->_output = $this->_detect('template', $path, $setupArray);
	}
}

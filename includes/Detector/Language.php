<?php
namespace Redaxscript\Detector;

use Redaxscript\Filter;
use Redaxscript\Model;
use function substr;

/**
 * children class to detect the current language
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Detector
 * @author Henry Ruhs
 */

class Language extends DetectorAbstract
{
	/**
	 * automate run
	 *
	 * @since 2.1.0
	 */

	public function autorun() : void
	{
		$settingModel = new Model\Setting();
		$contentModel = new Model\Content();
		$specialFilter = new Filter\Special();
		$dbStatus = $this->_registry->get('dbStatus');
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');
		$path = 'languages' . DIRECTORY_SEPARATOR . $this->_filePlaceholder . '.json';
		$setupArray =
		[
			'query' => $specialFilter->sanitize($this->_request->getQuery('l')),
			'session' => $this->_request->getSession('language'),
			'contents' => $contentModel->getByTableAndId($lastTable, $lastId)->language,
			'settings' => $dbStatus === 2 ? $settingModel->get('language') : null,
			'browser' => substr($this->_request->getServer('HTTP_ACCEPT_LANGUAGE'), 0, 2),
			'fallback' => 'en'
		];

		/* detect language */

		$this->_output = $this->_detect('language', $path, $setupArray);
	}
}

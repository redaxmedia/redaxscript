<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Detector as BaseDetector;

/**
 * children class to boot the detector
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Detector extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	protected function _autorun()
	{
		$detectorLanguage = new BaseDetector\Language($this->_registry, $this->_request);
		$detectorTemplate = new BaseDetector\Template($this->_registry, $this->_request);

		/* set registry */

		$this->_registry->set('language', $detectorLanguage->getOutput());
		$this->_registry->set('template', $detectorTemplate->getOutput());
	}
}

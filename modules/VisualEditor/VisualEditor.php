<?php
namespace Redaxscript\Modules\VisualEditor;

use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Modules;
use function method_exists;

/**
 * publish content with perfect style
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class VisualEditor extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Visual Editor',
		'alias' => 'VisualEditor',
		'author' => 'Redaxmedia',
		'description' => 'Publish content with perfect style',
		'version' => '4.6.0',
		'license' => 'Sponsor'
	];

	/**
	 * renderStart
	 *
	 * @since 4.3.0
	 */

	public function renderStart() : void
	{
		/* link */

		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('modules/VisualEditor/dist/styles/visual-editor.min.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'modules/VisualEditor/assets/scripts/init.js',
				'modules/VisualEditor/dist/scripts/visual-editor.min.js'
			]);
	}

	/**
	 * install the module
	 *
	 * @since 4.3.0
	 *
	 * @return bool
	 */

	public function install() : bool
	{
		$imageUpload = new Modules\ImageUpload\ImageUpload($this->_registry, $this->_request, $this->_language, $this->_config);
		$dialog = new Modules\Dialog\Dialog($this->_registry, $this->_request, $this->_language, $this->_config);
		if (method_exists('Redaxscript\Modules\CodeEditor\CodeEditor', 'uninstall'))
		{
			$codeEditor = new Modules\CodeEditor\CodeEditor($this->_registry, $this->_request, $this->_language, $this->_config);
			$codeEditor->uninstall();
		}
		return $imageUpload->reinstall() && $dialog->reinstall() && parent::install();
	}
}

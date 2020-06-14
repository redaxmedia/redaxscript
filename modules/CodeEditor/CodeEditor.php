<?php
namespace Redaxscript\Modules\CodeEditor;

use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Modules;
use function method_exists;

/**
 * publish content with perfect code
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CodeEditor extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Code Editor',
		'alias' => 'CodeEditor',
		'author' => 'Redaxmedia',
		'description' => 'Publish content with perfect code',
		'version' => '4.3.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token'))
		{
			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/CodeEditor/dist/styles/ace.min.css');

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.11/ace.js',
					'modules/CodeEditor/assets/scripts/init.js',
					'modules/CodeEditor/dist/scripts/code-editor.min.js'
				]);
		}
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
		if (method_exists('Redaxscript\Modules\VisualEditor\VisualEditor', 'uninstall'))
		{
			$visualEditor = new Modules\VisualEditor\VisualEditor($this->_registry, $this->_request, $this->_language, $this->_config);
			$visualEditor->uninstall();
		}
		return parent::install();
	}
}

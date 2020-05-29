<?php
namespace Redaxscript\Modules\VisualEditor;

use Redaxscript\Head;
use Redaxscript\Module;

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
		'version' => '4.3.0'
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
}

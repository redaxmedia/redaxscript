<?php
namespace Redaxscript\Modules\TextareaResizer;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * adjust the textarea to fit content
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class TextareaResizer extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Textarea Resizer',
		'alias' => 'TextareaResizer',
		'author' => 'Redaxmedia',
		'description' => 'Adjust the textarea to fit content',
		'version' => '4.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart()
	{
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile('https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js')
			->appendFile('modules/TextareaResizer/assets/scripts/init.js')
			->appendFile('modules/TextareaResizer/dist/scripts/textarea-resizer.min.js');
	}
}

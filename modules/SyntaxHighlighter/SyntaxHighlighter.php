<?php
namespace Redaxscript\Modules\SyntaxHighlighter;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * javascript powered syntax highlighter
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class SyntaxHighlighter extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Syntax Highlighter',
		'alias' => 'SyntaxHighlighter',
		'author' => 'Redaxmedia',
		'description' => 'JavaScript powered syntax highlighter',
		'version' => '4.1.0'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart() : void
	{
		/* link */

		$link = Head\Link::getInstance();
		$link
			->init()
			->appendFile('https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/atom-one-dark.min.css');

		/* script */

		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js',
				'modules/SyntaxHighlighter/assets/scripts/init.js',
				'modules/SyntaxHighlighter/dist/scripts/syntax-highlighter.min.js'
			]);
	}
}

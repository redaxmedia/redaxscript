<?php
namespace Redaxscript\Modules\Disqus;

use Redaxscript\Head;
use Redaxscript\Html;

/**
 * replace comments with disqus
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Disqus extends Config
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Disqus',
		'alias' => 'Disqus',
		'author' => 'Redaxmedia',
		'description' => 'Replace comments with Disqus',
		'version' => '4.0.0'
	];

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public function renderStart()
	{
		$this->_registry->set('commentReplace', true);
	}

	/**
	 * commentReplace
	 *
	 * @since 4.0.0
	 *
	 * @return string
	*/

	public function commentReplace() : string
	{
		$script = Head\Script::getInstance();
		$script
			->init('foot')
			->appendFile(
			[
				$this->_configArray['url'],
				'modules/Disqus/assets/scripts/init.js'
			]);

		/* html element */

		$boxElement = new Html\Element();
		$boxElement->init('div',
		[
			'id' => $this->_configArray['id']
		]);

		/* collect output */

		$output = $boxElement;
		return $output;
	}
}

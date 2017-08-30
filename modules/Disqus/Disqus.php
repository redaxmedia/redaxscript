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
		'description' => 'Replace comments with disqus',
		'version' => '3.2.3'
	];

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('articleId'))
		{
			$this->_registry->set('commentReplace', true);

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile($this->_configArray['url'])
				->appendFile('modules/Disqus/assets/scripts/init.js');
		}
	}

	/**
	 * commentReplace
	 *
	 * @since 2.2.0
	*/

	public function commentReplace()
	{
		$boxElement = new Html\Element();
		$boxElement->init('div',
		[
			'id' => $this->_configArray['id']
		]);

		/* collect output */

		$output = $boxElement;
		echo $output;
	}
}

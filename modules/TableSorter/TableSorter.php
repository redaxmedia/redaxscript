<?php
namespace Redaxscript\Modules\TableSorter;

use Redaxscript\Head;
use Redaxscript\Module;

/**
 * javaScript powered table sorter
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class TableSorter extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Table Sorter',
		'alias' => 'TableSorter',
		'author' => 'Redaxmedia',
		'description' => 'JavaScript powered table sorter',
		'version' => '4.0.0',
		'access' => 1
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token'))
		{
			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/TableSorter/dist/styles/table-sorter.min.css');

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js')
				->appendFile('modules/TableSorter/assets/scripts/init.js')
				->appendFile('modules/TableSorter/dist/scripts/table-sorter.min.js');
		}
	}
}

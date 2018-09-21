<?php
namespace Redaxscript\Modules\TableSorter;

use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Header;
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

			/* handle sort */

			if ($this->_registry->get('firstParameter') === 'table-sorter' && $this->_registry->get('secondParameter') === 'sort' && $this->_registry->get('lastParameter') === $this->_registry->get('token'))
			{
				$this->_registry->set('renderBreak', true);
				echo $this->_sort();
			}
		}
	}

	/**
	 * sort
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _sort() : ?string
	{
		$postArray = $this->_sanitizePost();
		$previous = Db::forTablePrefix($postArray['table'])->whereIdIs($postArray['previousId'])->findOne();
		$next = Db::forTablePrefix($postArray['table'])->whereIdIs($postArray['nextId'])->findOne();
		$current = Db::forTablePrefix($postArray['table'])->whereIdIs($postArray['currentId'])->findOne()->set('rank', $previous ? $previous->rank + 1 : $next->rank - 1);
		$status = $current->save();

		/* handle response */

		if ($status)
		{
			return json_encode(
			[
				'current' =>
				[
					'id'=> $current->id,
					'rank'=> $current->rank
				],
				'previous' =>
				[
					'id'=> $previous->id,
					'rank'=> $previous->rank
				],
				'next' =>
				[
					'id'=> $next->id,
					'rank'=> $next->rank
				]
			]);
		}
		Header::statusCode(404);
		return null;
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$numberFilter = new Filter\Number();
		$specialFilter = new Filter\Special();
		$content = file_get_contents('php://input');
		$postArray = (array)json_decode($content);

		/* sanitize post */

		return
		[
			'table' => $specialFilter->sanitize($postArray['table']),
			'currentId' => $numberFilter->sanitize($postArray['currentId']),
			'previousId' => $numberFilter->sanitize($postArray['previousId']),
			'nextId' => $numberFilter->sanitize($postArray['nextId'])
		];
	}
}

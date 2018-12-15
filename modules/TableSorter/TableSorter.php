<?php
namespace Redaxscript\Modules\TableSorter;

use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Header;
use Redaxscript\Module;
use function array_search;
use function file_get_contents;
use function json_decode;
use function json_encode;

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
		'version' => '4.0.0'
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
				->appendFile(
				[
					'https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js',
					'modules/TableSorter/assets/scripts/init.js',
					'modules/TableSorter/dist/scripts/table-sorter.min.js'
				]);

			/* router */

			if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'table-sorter' && $this->_registry->get('thirdParameter') === 'sort' && $this->_registry->get('tokenParameter'))
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
		if ($this->_request->getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest')
		{
			$postArray = $this->_sanitizePost();
			$contents = Db::forTablePrefix($postArray['table'])->whereIn('id', $postArray['rankArray'])->findMany();

			/* process contents */

			foreach ($contents as $value)
			{
				$value->set('rank', array_search($value->id, $postArray['rankArray']));
			}

			/* handle response */

			if ($contents->save())
			{
				Header::contentType('application/json');
				return json_encode($postArray['rankArray']);
			}
		}
		Header::responseCode(404);
		exit;
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
		$specialFilter = new Filter\Special();
		$content = file_get_contents('php://input');
		$postArray = (array)json_decode($content);

		/* sanitize post */

		return
		[
			'table' => $specialFilter->sanitize($postArray['table']),
			'rankArray' => $postArray['rankArray']
		];
	}
}

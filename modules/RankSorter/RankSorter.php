<?php
namespace Redaxscript\Modules\RankSorter;

use Redaxscript\Db;
use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Header;
use Redaxscript\Module;
use function array_search;
use function json_encode;

/**
 * adjust the rank with draggable table rows
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class RankSorter extends Module\Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Rank Sorter',
		'alias' => 'RankSorter',
		'author' => 'Redaxmedia',
		'description' => 'Adjust the rank with draggable table rows',
		'version' => '4.5.0',
		'license' => 'MIT'
	];

	/**
	 * renderStart
	 *
	 * @since 4.0.0
	 */

	public function renderStart() : void
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token'))
		{
			/* link */

			$link = Head\Link::getInstance();
			$link
				->init()
				->appendFile('modules/RankSorter/dist/styles/rank-sorter.min.css');

			/* script */

			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile(
				[
					'https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js',
					'modules/RankSorter/assets/scripts/init.js',
					'modules/RankSorter/dist/scripts/rank-sorter.min.js'
				]);

			/* route as needed */

			if ($this->_registry->get('firstParameter') === 'module' && $this->_registry->get('secondParameter') === 'rank-sorter' && $this->_registry->get('thirdParameter') === 'sort' && $this->_registry->get('tokenParameter'))
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

		/* sanitize post */

		return
		[
			'table' => $specialFilter->sanitize($this->_request->getStream('table')),
			'rankArray' => $this->_request->getStream('rankArray')
		];
	}
}

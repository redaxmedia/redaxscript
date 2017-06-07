<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Messenger;
use Redaxscript\Filter;
use Redaxscript\Validator;
use Redaxscript\View;

/**
 * children class to process the search request
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class Search extends ControllerAbstract
{
	/**
	 * array of tables
	 *
	 * @var array
	 */

	protected $tableArray =
	[
		'categories',
		'articles',
		'comments'
	];

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		$searchFilter = new Filter\Search();
		$secondParameter = $searchFilter->sanitize($this->_registry->get('secondParameter'));
		$thirdParameter = $searchFilter->sanitize($this->_registry->get('thirdParameter'));

		/* process query */

		$queryArray = [];
		if (!$thirdParameter)
		{
			$queryArray =
			[
				'table' => $this->tableArray,
				'search' => str_replace('-', ' ', $secondParameter)
			];
		}
		else if (in_array($secondParameter, $this->tableArray))
		{
			$queryArray =
			[
				'table' =>
				[
					$secondParameter
				],
				'search' => str_replace('-', ' ', $thirdParameter)
			];
		}

		/* process search */

		$resultArray = $this->_search(
		[
			'table' => $queryArray['table'],
			'search' => $queryArray['search']
		]);

		/* handle info */

		$messageArray = $this->_validate($queryArray, $resultArray);
		if ($messageArray)
		{
			return $this->_info(
			[
				'message' => $messageArray
			]);
		}

		/* handle result */

		$output = $this->_renderResult($resultArray);
		if ($output)
		{
			return $output;
		}
		return $this->_info(
		[
			'message' => $this->_language->get('search_no')
		]);
	}

	/**
	 * render the result
	 *
	 * @since 3.0.0
	 *
	 * @param array $resultArray array of the result
	 *
	 * @return string
	 */

	protected function _renderResult($resultArray = [])
	{
		$searchList = new View\ResultList($this->_registry, $this->_language);
		return $searchList->render($resultArray);
	}

	/**
	 * show the info
	 *
	 * @since 3.0.0
	 *
	 * @param array $infoArray array of the info
	 *
	 * @return string
	 */

	protected function _info($infoArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setUrl($this->_language->get('back'), $this->_registry->get('root'))
			->info($infoArray['message'], $this->_language->get('something_wrong'));
	}

	/**
	 * validate
	 *
	 * @since 3.0.0
	 *
	 * @param array $queryArray array of the query
	 * @param array $resultArray array of the result
	 *
	 * @return array
	 */

	protected function _validate($queryArray = [], $resultArray = [])
	{
		$searchValidator = new Validator\Search();

		/* validate query */

		$messageArray = [];
		if ($searchValidator->validate($queryArray['search'], $this->_language->get('search')) === Validator\ValidatorInterface::FAILED)
		{
			$messageArray[] = $this->_language->get('input_incorrect');
		}

		/* validate result */

		if (!$resultArray)
		{
			$messageArray[] = $this->_language->get('search_no');
		}
		return $messageArray;
	}

	/**
	 * search in tables
	 *
	 * @since 3.0.0
	 *
	 * @param array $searchArray array of the search
	 *
	 * @return array
	 */

	protected function _search($searchArray = [])
	{
		$resultArray = [];

		/* process table */

		if (is_array($searchArray['table']))
		{
			foreach ($searchArray['table'] as $table)
			{
				$resultArray[$table] = Db::forTablePrefix($table)
					->whereLikeMany($this->_getColumnArray($table), $this->_getLikeArray($table, $searchArray['search']))
					->where('status', 1)
					->whereLanguageIs($this->_registry->get('language'))
					->orderByDesc('date')
					->findMany();
			}
		}
		return $resultArray;
	}

	/**
	 * get the column array
	 *
	 * @since 3.0.0
	 *
	 * @param string $table name of the table
	 *
	 * @return array
	 */

	protected function _getColumnArray($table = null)
	{
		return array_filter(
		[
			$table === 'categories' || $table === 'articles' ? 'title' : null,
			$table === 'categories' || $table === 'articles' ? 'description' : null,
			$table === 'categories' || $table === 'articles' ? 'keywords' : null,
			$table === 'articles' || $table === 'comments' ? 'text' : null
		]);
	}

	/**
	 * get the like array
	 *
	 * @since 3.1.0
	 *
	 * @param string $table name of the table
	 * @param array $search value of the search
	 *
	 * @return array
	 */

	protected function _getLikeArray($table = null, $search = null)
	{
		return array_filter(
		[
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'articles' || $table === 'comments' ? '%' . $search . '%' : null
		]);
	}
}
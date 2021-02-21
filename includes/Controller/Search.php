<?php
namespace Redaxscript\Controller;

use Redaxscript\Filter;
use Redaxscript\Model;
use Redaxscript\Validator;
use Redaxscript\View;
use function array_key_exists;
use function in_array;
use function is_array;
use function str_replace;

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
	 * array of the tables
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

	public function process() : string
	{
		$queryArray = $this->_sanitizeQuery();

		/* validate query */

		$validateArray = $this->_validateQuery($queryArray);
		if ($validateArray)
		{
			return $this->_info(
			[
				'message' => $validateArray
			]);
		}

		/* handle search */

		$resultArray = $this->_search(
		[
			'table' => $queryArray['table'],
			'search' => $queryArray['search'],
			'language' => $this->_registry->get('language')
		]);
		$output = $resultArray ? $this->_renderResult($resultArray) : null;
		if ($output)
		{
			return $output;
		}

		/* handle info */

		return $this->_info(
		[
			'message' => $this->_language->get('search_no')
		]);
	}

	/**
	 * sanitize the query
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizeQuery() : array
	{
		$aliasFilter = new Filter\Alias();
		$secondParameter = $aliasFilter->sanitize($this->_registry->get('secondParameter'));
		$thirdParameter = $aliasFilter->sanitize($this->_registry->get('thirdParameter'));

		/* process query */

		if (!$thirdParameter)
		{
			return
			[
				'table' => $this->tableArray,
				'search' => str_replace('-', ' ', $secondParameter)
			];
		}
		if (in_array($secondParameter, $this->tableArray))
		{
			return
			[
				'table' =>
				[
					$secondParameter
				],
				'search' => str_replace('-', ' ', $thirdParameter)
			];
		}
		return [];
	}

	/**
	 * validate the query
	 *
	 * @since 3.0.0
	 *
	 * @param array $queryArray array of the query
	 *
	 * @return array
	 */

	protected function _validateQuery(array $queryArray = []) : array
	{
		$aliasValidator = new Validator\Alias();
		$validateArray = [];

		/* validate query */

		if (!$queryArray['search'])
		{
			$validateArray[] = $this->_language->get('input_empty');
		}
		else if (!$aliasValidator->validate($queryArray['search']))
		{
			$validateArray[] = $this->_language->get('input_incorrect');
		}
		return $validateArray;
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

	protected function _search(array $searchArray = []) : array
	{
		$searchModel = new Model\Search();
		$resultArray = [];

		/* process table */

		if (array_key_exists('table', $searchArray) && is_array($searchArray['table']) && array_key_exists('search', $searchArray) && array_key_exists('language', $searchArray))
		{
			foreach ($searchArray['table'] as $table)
			{
				$resultArray[$table] = $searchModel->getByTable($table, $searchArray['search'], $searchArray['language']);
			}
		}
		return $resultArray;
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

	protected function _renderResult(array $resultArray = []) : string
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

	protected function _info(array $infoArray = []) : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger
			->setUrl($this->_language->get('back'), $this->_registry->get('root'))
			->info($infoArray['message'], $this->_language->get('something_wrong'));
	}

}

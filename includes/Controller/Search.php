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

	protected $tableArray = array(
		'categories',
		'articles',
		'comments'
	);

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function process()
	{
		$specialFilter = new Filter\Special();
		$secondParameter = $specialFilter->sanitize($this->_registry->get('secondParameter'));
		$thirdParameter = $specialFilter->sanitize($this->_registry->get('thirdParameter'));

		/* process query */

		$queryArray = array();
		if (!$thirdParameter)
		{
			$queryArray = array(
				'table' => $this->tableArray,
				'search' => $secondParameter
			);
		}
		else if (in_array($secondParameter, $this->tableArray))
		{
			$queryArray = array(
				'table' => array(
					$secondParameter
				),
				'search' => $thirdParameter
			);
		}

		/* process search */

		$resultArray = $this->_search(array(
			'table' => $queryArray['table'],
			'search' => $queryArray['search']
		));

		/* handle info */

		$messageArray = $this->_validate($queryArray, $resultArray);
		if ($messageArray)
		{
			return $this->_info(array(
				'message' => $messageArray
			));
		}

		/* handle result */

		$output = $this->_result($resultArray);
		if ($output)
		{
			return $output;
		}
		return $this->_info(array(
			'message' => $this->_language->get('search_no')
		));
	}

	/**
	 * show the result
	 *
	 * @since 3.0.0
	 *
	 * @param array $resultArray array of the result
	 *
	 * @return string
	 */

	protected function _result($resultArray = array())
	{
		$listSearch = new View\SearchList($this->_registry, $this->_language);
		return $listSearch->render($resultArray);
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

	protected function _info($infoArray = array())
	{
		$messenger = new Messenger();
		return $messenger
			->setAction($this->_language->get('back'), 'home')
			->info($infoArray['message'], $this->_language->get('error_occurred'));
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

	protected function _validate($queryArray = array(), $resultArray = array())
	{
		$searchValidator = new Validator\Search();

		/* validate query */

		$messageArray = array();
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
	 * @param array $searchArray array of the search
	 *
	 * @return array
	 */

	protected function _search($searchArray = array())
	{
		$resultArray = array();

		/* process tables */

		foreach ($searchArray['table'] as $table)
		{
			$columnArray = array_filter(array(
				$table === 'categories' || $table === 'articles' ? 'title' : null,
				$table === 'categories' || $table === 'articles' ? 'description' : null,
				$table === 'categories' || $table === 'articles' ? 'keywords' : null,
				$table === 'articles' || $table === 'comments' ? 'text' : null
			));
			$likeArray = array_filter(array(
				$table === 'categories' || $table === 'articles' ? '%' . $searchArray['search'] . '%' : null,
				$table === 'categories' || $table === 'articles' ? '%' . $searchArray['search'] . '%' : null,
				$table === 'categories' || $table === 'articles' ? '%' . $searchArray['search'] . '%' : null,
				$table === 'articles' || $table === 'comments' ? '%' . $searchArray['search'] . '%' : null
			));

			/* fetch result */

			$resultArray[$table] = Db::forTablePrefix($table)
				->whereLikeMany($columnArray, $likeArray)
				->where('status', 1)
				->whereLanguageIs($this->_registry->get('language'))
				->orderByDesc('date')
				->findMany();
		}
		return $resultArray;
	}
}
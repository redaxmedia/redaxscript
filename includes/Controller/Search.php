<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Messenger;
use Redaxscript\Html;
use Redaxscript\Filter;
use Redaxscript\Registry;
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

class Search implements ControllerInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

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
	 * constructor of the class
	 *
	 * @since 3.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

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
		$searchValidator = new Validator\Search();
		$secondParameter = $specialFilter->sanitize($this->_registry->get('secondParameter'));

		/* process query */

		if (!$this->_registry->get('thirdParameter'))
		{
			$queryArray = array(
				'table' => $this->tableArray,
				'search' => $this->_registry->get('secondParameter')
			);
		}
		else if (in_array($secondParameter, $this->tableArray))
		{
			$queryArray = array(
				'table' => array(
					$secondParameter
				),
				'search' => $this->_registry->get('thirdParameter')
			);
		}

		/* validate search */

		if ($searchValidator->validate($queryArray['search'], $this->_language->get('search')) === Validator\ValidatorInterface::FAILED)
		{
			$errorArray[] = $this->_language->get('input_incorrect');
		}

		/* process search */

		//TODO: pass the whole $queryArray to _search and let it do the processing
		//$result = $this->_search($queryArray);
		foreach ($queryArray['table'] as $table)
		{
			$result[] = $this->_search($table, $queryArray['search']);
		}
		if (!$result)
		{
			$errorArray[] = $this->_language->get('search_no');
		}

		/* handle error */

		if ($errorArray)
		{
			return $this->error($errorArray);
		}
		//TODO: The check for $output does not "feel right" to me - can't we check for $result
		//and if that is present we run $this->success($result) ... we don't need the $queryArray because each
		//DB object contains the tablename inside! What about renaming it to $successArray like in other controllers?
		//because it is a set of result objects and not a single result
		$output = $this->success($result, $queryArray);
		if ($output)
		{
			return $output;
		}
		return $this->error($this->_language->get('search_no'));
	}

	/**
	 * fetch the search result
	 *
	 * @param string $table name of the table
	 * @param string $search value of the search
	 *
	 * @return Db
	 */

	private function _search($table = null, $search = null)
	{
		$columnArray = array_filter(array(
			$table === 'categories' || $table === 'articles' ? 'title' : null,
			$table === 'categories' || $table === 'articles' ? 'description' : null,
			$table === 'categories' || $table === 'articles' ? 'keywords' : null,
			$table === 'articles' || $table === 'comments' ? 'text' : null
		));
		$likeArray = array_filter(array(
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
			$table === 'articles' || $table === 'comments' ? '%' . $search . '%' : null
		));

		/* fetch result */

		return Db::forTablePrefix($table)
			->whereLikeMany($columnArray, $likeArray)
			->where('status', 1)
			->orderByDesc('date')
			->findMany();
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @param array $result array of the db query
	 * @param array $queryArray array of the tables and
	 *
	 * @return string
	 */
	//TODO: Rewrite to use $successArray
	public function success($result = array(), $queryArray = array())
	{
		$listSearch = new View\SearchList($this->_registry, $this->_language);
		return $listSearch->render($result, $queryArray['table']);
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('back'), 'home')->info($errorArray, $this->_language->get('error_occurred'));
	}
}
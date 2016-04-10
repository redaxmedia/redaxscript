<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Messenger;
use Redaxscript\Html;
use Redaxscript\Filter;
use Redaxscript\Registry;
use Redaxscript\Request;
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
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * array for searchable tables
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
	 * @param Request $request instance of the registry class
	 */

	public function __construct(Registry $registry, Language $language, Request $request)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_request = $request;
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

		/* process search parameters */

		if (!$this->_registry->get('thirdParameter'))
		{
			$queryArray = array(
				'search' => $this->_registry->get('secondParameter')
			);
		}
		else if (in_array($specialFilter->sanitize($this->_registry->get('secondParameter')), $this->tableArray))
		{
			$queryArray = array(
				'table' => $specialFilter->sanitize($this->_registry->get('secondParameter')),
				'search' => $this->_registry->get('thirdParameter')
			);
		}

		/* validate search terms */

		if (strlen($queryArray['search']) < 3 || $queryArray['search'] == $this->_language->get('search'))
		{
			$errorArray[] = $this->_language->get('input_incorrect');
		}

		/* get search query */

		$result = $this->_search($queryArray);

		if (!$result)
		{
			$errorArray[] = $this->_language->get('search_no');
		}

		/* handle error */

		if ($errorArray)
		{
			return $this->error($errorArray);
		}

		$output = $this->success($result, $queryArray);

		if ($output)
		{
			return $output;
		}
		else
		{
			return $this->error($this->_language->get('search_no'));
		}
	}

	/**
	 * method for getting the search result
	 *
	 * @param array $queryArray
	 *
	 * @return array
	 */

	private function _search($queryArray = array())
	{
		$table = $queryArray['table'] ? $queryArray['table'] : $this->tableArray;
		$search = $queryArray['search'];
		$result = null;

		if (is_array($table))
		{
			foreach ($table as $value)
			{

				$query = Db::forTablePrefix($value)->where('status', 1)
					->whereRaw('(language = ? OR language is ?)', array(
						$this->_registry->get('language'),
						null
					));

				switch ($value)
				{
					case 'articles':
						$query->whereLikeMany(array(
							'title',
							'description',
							'keywords',
							'text'
						), array(
							'%' . $search . '%',
							'%' . $search . '%',
							'%' . $search . '%',
							'%' . $search . '%'
						));
						break;
					case 'categories':
						$query->whereLikeMany(array(
							'title',
							'description',
							'keywords'
						), array(
							'%' . $search . '%',
							'%' . $search . '%',
							'%' . $search . '%'
						));
						break;
					case 'comments':
						$query->whereLikeMany(array(
							'text'
						), array(
							'%' . $search . '%'
						));
						break;
				}

				$result[$value] = $query->orderByDesc('date')
					->findArray();
			}
		}
		else
		{
			$query = Db::forTablePrefix($table)->where(array(
				'status' => 1
			))
				->whereRaw('(language = ? OR language is ?)', array(
					$this->_registry->get('language'),
					null
				));

			switch ($table)
			{
				case 'articles':
					$query->whereLikeMany(array(
						'title',
						'description',
						'keywords',
						'text'
					), array(
						'%' . $search . '%',
						'%' . $search . '%',
						'%' . $search . '%',
						'%' . $search . '%'
					));
					break;
				case 'categories':
					$query->whereLikeMany(array(
						'title',
						'description',
						'keywords'
					), array(
						'%' . $search . '%',
						'%' . $search . '%',
						'%' . $search . '%'
					));
					break;
				case 'comments':
					$query->whereLikeMany(array(
						'text'
					), array(
						'%' . $search . '%'
					));
					break;
			}

			$result = $query->orderByDesc('date')
				->findArray();
		}

		return $result;
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @param array $result
	 * @param array $queryArray
	 *
	 * @return string
	 */

	public function success($result = array(), $queryArray = array())
	{
		$listSearch = new View\SearchList($this->_registry, $this->_language);
		return $listSearch->render($result, $queryArray);
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return string
	 */

	public function error($errorArray = array())
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('back'), 'home')->info($errorArray, $this->_language->get('error_occurred'));
	}
}
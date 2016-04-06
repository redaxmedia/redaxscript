<?php
namespace Redaxscript\Controller;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Messenger;
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
		$tables = array('articles', 'comments', 'users');

		/* process search parameters */

		if (!$this->_registry->get('thirdParameter'))
		{
			$getArray = array(
				'search_terms' => $this->_registry->get('secondParameter')
			);
		}
		else if (in_array($specialFilter->sanitize($this->_registry->get('secondParameter')), $tables))
		{
			$getArray = array(
				'table' => $specialFilter->sanitize($this->_registry->get('secondParameter')),
				'search_terms' => $this->_registry->get('thirdParameter')
			);
		}
		else
		{
			return $this->error($this->_language->get('something_wrong'));
		}

		/* validate search terms */
		if (strlen($getArray['search_terms']) < 3 || $getArray['search_terms'] == $this->_language->get('search_terms'))
		{
			return $this->error($this->_language->get('input_incorrect'));
		}

		/* get search query */

		$result = $this->_search($getArray);
		if (!$result)
		{
			echo $getArray['table'];
			return $this->error($this->_language->get('search_no'));
		}

		$listForm = new View\SearchList($this->_registry, $this->_language);

		return $listForm->render($getArray, $result);
	}

	/**
	 * Method for getting the search result
	 *
	 * @param array $getArray
	 *
	 * @return array
	 */

	private function _search($getArray = array())
	{
		if (!$getArray['table'])
		{
			// TODO: search in every table
			$query = Db::forTablePrefix('articles');
		}
		else
		{
			$query = Db::forTablePrefix($getArray['table']);
		}

		return $query->where('status', 1)
			->whereRaw('(language = ? OR language is ?)', array(
				$this->_registry->get('language'),
				null
			))
			->whereLikeMany(array(
				'title',
				'description',
				'keywords',
				'text'
			), array(
				'%' . $getArray['search_terms'] . '%',
				'%' . $getArray['search_terms'] . '%',
				'%' . $getArray['search_terms'] . '%',
				'%' . $getArray['search_terms'] . '%'
			))
			->orderByDesc('date')
			->findArray();
	}

	/**
	 * show the success
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function success()
	{
		$messenger = new Messenger();
		return $messenger->setAction($this->_language->get('continue'), 'login')->doRedirect(0)->success($this->_language->get('logged_out'), $this->_language->get('goodbye'));
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
		return $messenger->setAction($this->_language->get('back'), 'home')->error($errorArray, $this->_language->get('error_occurred'));
	}
}
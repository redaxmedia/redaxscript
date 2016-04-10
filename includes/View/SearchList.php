<?php
namespace Redaxscript\View;

use Redaxscript\Db;
use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Validator;

/**
 * children class to generate the search list
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class SearchList implements ViewInterface
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
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * render the view
	 *
	 * @param array $resultArray
	 * @param null $queryArray
	 *
	 * @return string
	 * @since 3.0.0
	 *
	 */

	public function render($resultArray = null, $queryArray = null)
	{
		$accessValidator = new Validator\Access();

		$output = Hook::trigger('searchListStart');

		$itemOutput = null;

		if (!$queryArray['table'])
		{
			$queryArray['table'] = $this->tableArray;
		}

		foreach ($resultArray as $result => $item)
		{
			if ($item != null && $accessValidator->validate($item['access'], Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
			{
				/* title element */

				$titleElement = new Html\Element();
				$titleElement
					->init('h2', array(
						'class' => 'rs-title-content rs-title-result'
					))
					->text($this->_language->get(is_array($queryArray['table']) ? $result : 'search'));

				/* list element */

				$listElement = new Html\Element();
				$listElement
					->init('ol', array(
						'class' => 'rs-list-result'
					));

				if (is_array($queryArray['table']) && $item != null)
				{
					$itemOutput = $this->renderMultiple($result, $item);
				}
				else if (!is_array($queryArray['table']))
				{
					$itemOutput = $this->renderSingle($item, $queryArray['table']);
				}
				$listElement->html($itemOutput);

				$output .= $titleElement . $listElement;

				$itemOutput = null;
			}
		}

		$output .= Hook::trigger('searchListEnd');
		return $output;
	}

	/**
	 * method for rendering list
	 *
	 * @param array $item
	 * @param string $table
	 *
	 * @return string
	 */

	private function renderSingle($item, $table)
	{
		$accessValidator = new Validator\Access();

		/* access granted */

		if ($accessValidator->validate($item['access'], Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
		{
			/* prepare metadata */

			$date = date(Db::getSetting('date'), strtotime($item['date']));

			/* build route */

			if ($table == 'categories' && $item['parent'] == 0 || $item['table'] == 'articles' && $item['category'] == 0)
			{
				$route = $item['alias'];
			}
			else
			{
				$route = build_route($table, $item['id']);
			}

			/* html element */

			$linkElement = new Html\Element();
			$linkElement
				->init('a', array(
					'href' => $this->_registry->get('parameterRoute') . $route,
					'class' => 'rs-link-result'
				))
				->text($table != 'comments' ? $item['title'] : substr($item['text'], 0, 20));

			$textElement = new Html\Element();
			$textElement
				->init('span', array(
					'class' => 'rs-text-result-date'
				))
				->text($date);

			$itemElement = new Html\Element();
			$itemElement
				->init('li', array(
					'class' => 'rs-item-result'
				))
				->html($linkElement . $textElement);

			/* return output */

			return $itemElement;
		}
		return null;
	}

	/**
	 * method for rendering multiple category list
	 *
	 * @param string $table
	 * @param array $row
	 *
	 * @return string
	 */

	private function renderMultiple($table, $row)
	{
		$accessValidator = new Validator\Access();
		$itemOutput = null;

		foreach ($row as $item)
		{
			/* access granted */

			if ($accessValidator->validate($item->access, Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
			{
				/* prepare metadata */

				if (!$item['title'])
				{
					$item['title'] = substr($item['text'], 0, 20);
				}

				$date = date(Db::getSetting('date'), strtotime($item['date']));

				/* build route */

				if ($table === 'categories' && $item['parent'] === 0 || $item['table'] === 'articles' && $item['category'] === 0)
				{
					$route = $item['alias'];
				}
				else
				{
					$route = build_route($table, $item['id']);
				}

				/* html element */

				$linkElement = new Html\Element();
				$linkElement
					->init('a', array(
						'href' => $this->_registry->get('parameterRoute') . $route,
						'class' => 'rs-link-result'
					))
					->text($item['title']);

				$textElement = new Html\Element();
				$textElement
					->init('span', array(
						'class' => 'rs-text-result-date'
					))
					->text($date);

				$itemElement = new Html\Element();
				$itemElement
					->init('li', array(
						'class' => 'rs-item-result'
					))
					->html($linkElement . $textElement);

				/* return output */

				$itemOutput .= $itemElement;
			}
		}
		return $itemOutput;
	}
}

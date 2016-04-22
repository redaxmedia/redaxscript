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
	//TODO: language never was use, remove it here + use statement and inside the test suite
	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

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
	 * @param array $result array for the db query results
	 * @param array $tableArray array for the tables
	 *
	 * @return string
	 * @since 3.0.0
	 *
	 */

	public function render($result = null, $tableArray = null)
	{
		$output = Hook::trigger('searchListStart');
		$i = 0;
		//TODO: Please get rid of the $i construct here and try to check for the current $tablename in the DB objects
		foreach ($result as $item)
		{
			$itemOutput = null;
			if ($item)
			{
				/* title element */

				$titleElement = new Html\Element();
				$titleElement
					->init('h2', array(
						'class' => 'rs-title-content rs-title-result'
					))
					->text($this->_language->get(count($tableArray) > 1 ? $tableArray[$i] : 'search'));

				/* list element */

				$listElement = new Html\Element();
				$listElement
					->init('ol', array(
						'class' => 'rs-list-result'
					));

				/* generate category's list */

				foreach ($item as $value)
				{
					$itemOutput .= $this->_renderItem($value, $tableArray[$i]);
				}

				/* only show a category's results, when the user can access at least 1 result */
				//TODO: do we really need this if? The Element class can handle empty values :-) and is made
				//to work with null objects
				if ($itemOutput)
				{
					$listElement->html($itemOutput);
					$output .= $titleElement . $listElement;
				}
			}
			$i++;
		}
		$output .= Hook::trigger('searchListEnd');
		return $output;
	}

	/**
	 * method for rendering a list item
	 *
	 * @param array $item a single db row the $resultArray
	 * @param string $table to know which table the current $item from
	 *
	 * @return string
	 */

	protected function _renderItem($item = array(), $table = null)
	{
		$output = null;
		$accessValidator = new Validator\Access();
		if ($accessValidator->validate($item->access, Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
		{
			/* prepare metadata */

			$date = date(Db::getSetting('date'), strtotime($item->date));

			/* build route */
			//TODO: Just use $route = build_route($table, $item->id); the refactored buildrout is going to be
			//clever enough not to lookup a name and just use the alias - we can life with less performance fo now
			//and have cleaner code
			if ($table === 'categories' && $item->parent === 0 || $item === 'articles' && $item->category === 0)
			{
				$route = $item->alias;
			}
			else
			{
				$route = build_route($table, $item->id);
			}

			/* html element */

			$linkElement = new Html\Element();
			$linkElement
				->init('a', array(
					'href' => $this->_registry->get('parameterRoute') . $route,
					'class' => 'rs-link-result'
				))
				->text($table === 'comments' ? $item->author : $item->title);
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
			$output = $itemElement;
		}
		return $output;
	}
}

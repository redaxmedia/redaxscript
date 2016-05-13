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
	 * @since 3.0.0
	 * 
	 * @param array $result array for the db query results
	 *
	 * @return string
	 */

	//TODO: rename to $resultArray
	public function render($result = null)
	{
		$output = Hook::trigger('searchListStart');
		//Todo: debug if item is an array or a instance of Db, if so rename it to $result as it is a single result set
		foreach ($result as $item => $data)
		{
			$itemOutput = null;
			if ($data)
			{
				/* title element */
				//TODO: move the element building out - bad performance inside a foreach
				$titleElement = new Html\Element();
				$titleElement
					->init('h2', array(
						'class' => 'rs-title-content rs-title-result'
					))
					->text($this->_language->get($item));

				/* list element */

				$listElement = new Html\Element();
				$listElement
					->init('ol', array(
						'class' => 'rs-list-result'
					));

				/* generate category's list */

				foreach ($data as $value)
				{
					//TODO: what about the access check on this position - we save multiple new Validator\Access
					$itemOutput .= $this->_renderItem($value, $item);
				}

				/* only show a category's results, when the user can access at least 1 result */

				if ($itemOutput)
				{
					$listElement->html($itemOutput);
					$output .= $titleElement . $listElement;
				}
			}
		}
		$output .= Hook::trigger('searchListEnd');

		return $output;
	}

	/**
	 * method for rendering a list item
	 *
	 * @sind 3.0.0
	 *
	 * @param array $item a single db row the $resultArray
	 * @param string $table to know which table the current $item from
	 *
	 * @return string
	 */
	//Todo: debug if item is an array or a instance of Db, if so rename it to $result as it is a single result set
	protected function _renderItem($item = array(), $table = null)
	{
		$output = null;
		$accessValidator = new Validator\Access();
		if ($accessValidator->validate($item->access, Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
		{
			/* prepare metadata */

			$date = date(Db::getSetting('date'), strtotime($item->date));

			/* build route */

			$route = build_route($table, $item->id);

			/* html element */
			//TODO: move the element building outside of the access check
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

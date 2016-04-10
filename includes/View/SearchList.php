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
	 * @param array $resultArray
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render($resultArray = null)
	{
		$output = Hook::trigger('searchListStart');

		$accessValidator = new Validator\Access();
		$itemOutput = null;

		if (!$resultArray['table'])
		{
			$queryArray['table'] = 'articles';
		}

		/* title element */

		$titleElement = new Html\Element();
		$titleElement
			->init('h2', array(
				'class' => 'rs-title-content rs-title-result'
			))
			->text($this->_language->get('search'));

		/* list element */

		$listElement = new Html\Element();
		$listElement
			->init('ol', array(
				'class' => 'rs-list-result'
			));

		foreach ($resultArray as $result)
		{
			$access = $result['access'];

			/* access granted */

			if ($accessValidator->validate($access, Registry::get('myGroups')) === Validator\ValidatorInterface::PASSED)
			{
				/* prepare metadata */

				if (!$result['description'])
				{
					$result['description'] = $result['title'];
				}
				$date = date(Db::getSetting('date'), strtotime($result['date']));

				/* build route */

				if ($queryArray['table'] == 'categories' && $result['parent'] == 0 || $result['table'] == 'articles' && $result['category'] == 0)
				{
					$route = $result['alias'];
				}
				else
				{
					$route = build_route($queryArray['table'], $result['id']);
				}

				/* html element */

				$linkElement = new Html\Element();
				$linkElement
					->init('a', array(
						'href' => $this->_registry->get('parameterRoute') . $route,
						'class' => 'rs-link-result'
					))
					->text($result['title']);

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

				/* collect item output */

				$itemOutput .= $itemElement;
			}
		}

		$listElement->html($itemOutput);

		$output .= $titleElement . $listElement;
		$output .= Hook::trigger('searchListEnd');
		return $output;
	}
}

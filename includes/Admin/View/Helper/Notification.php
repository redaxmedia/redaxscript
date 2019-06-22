<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Module;
use function array_key_exists;
use function array_replace_recursive;
use function is_array;

/**
 * helper class to create the admin notification
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Notification implements Admin\View\ViewInterface
{
	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * array of the notification
	 *
	 * @var array
	 */

	protected $_notificationArray = [];

	/**
	 * options of the notification
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-admin-list-notification',
			'item' => 'rs-admin-item-notification rs-admin-item-note',
			'title' => 'rs-admin-title-notification',
			'link' => 'rs-admin-link-notification',
			'text' => 'rs-admin-text-notification',
			'note' =>
			[
				'success' => 'rs-admin-is-success',
				'warning' => 'rs-admin-is-warning',
				'error' => 'rs-admin-is-error',
				'info' => 'rs-admin-is-info'
			]
		]
	];

	/**
	 * constructor of the class
	 *
	 * @since 4.0.0
	 *
	 * @param Language $language instance of the language class
	 */

	public function __construct(Language $language)
	{
		$this->_language = $language;
	}

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the notification
	 *
	 * @return self
	 */

	public function init(array $optionArray = []) : self
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		$this->_notificationArray = Module\Hook::collect('adminNotification');
		return $this;
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	public function render() : ?string
	{
		$output = null;
		$outputItem = null;

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h3',
			[
				'class' => $this->_optionArray['className']['title']
			]);
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']
			]);
		$itemElement = $element
			->copy()
			->init('li',
			[
				'class' => $this->_optionArray['className']['item']
			]);
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => $this->_optionArray['className']['link']
			]);
		$textElement = $element
			->copy()
			->init('span',
			[
				'class' => $this->_optionArray['className']['text']
			]);

		/* process notification */

		foreach ($this->_notificationArray as $typeKey => $typeValue)
		{
			foreach ($typeValue as $notificationKey => $notificationValue)
			{
				foreach ($notificationValue as $value)
				{
					$outputItem .= $itemElement
						->copy()
						->addClass($this->_optionArray['className']['note'][$typeKey])
						->html(
							$titleElement->text($notificationKey)
						)
						->append(
							is_array($value) &&
							array_key_exists('text', $value) &&
							array_key_exists('attr', $value) ? $linkElement->attr($value['attr'])->text($value['text']) : $textElement->text($value)
						);
				}
			}
		}
		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		return $output;
	}

	/**
	 * get the meta array
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function getMetaArray() : array
	{
		$metaArray = [];

		/* process notification */

		foreach ($this->_notificationArray as $typeKey => $typeValue)
		{
			foreach ($typeValue as $notificationKey => $notificationValue)
			{
				$metaArray[$typeKey]++;
				$metaArray['total']++;
			}
		}
		return $metaArray;
	}
}

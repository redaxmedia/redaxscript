<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * helper class to create the admin dock
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Dock
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * options of the dock
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'wrapper' => 'rs-admin-wrapper-dock',
			'box' => 'rs-admin-js-dock rs-admin-box-dock rs-admin-fn-clearfix',
			'link' =>
			[
				'unpublish' => 'rs-admin-link-dock rs-admin-link-unpublish',
				'edit' => 'rs-admin-link-dock rs-admin-link-edit',
				'delete' => 'rs-admin-js-confirm rs-admin-link-dock rs-admin-link-delete'
			]
		]
	];

	/**
	 * constructor of the class
	 *
	 * @since 4.0.0
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
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the dock
	 */

	public function init(array $optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param string $id identifier of the item
	 *
	 * @return string
	 */

	public function render(string $table = null, string $id = null) : string
	{
		$output = Module\Hook::trigger('adminDockStart');
		$tableEdit = $this->_registry->get($table . 'Edit');
		$tableDelete = $this->_registry->get($table . 'Delete');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$token = $this->_registry->get('token');

		/* html element */

		$element = new Html\Element();
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['wrapper']
			]);
		$boxElement = $element
			->copy()
			->init('div',
			[
				'class' => $this->_optionArray['className']['box']
			]);
		$linkUnpublishElement = $element
			->copy()
			->init('a',
			[
				'href' => $parameterRoute . 'admin/unpublish/' . $table . '/' . $id . '/' . $token,
				'class' => $this->_optionArray['className']['link']['unpublish'],
				'data-description' => $this->_language->get('unpublish')
			])
			->text($this->_language->get('unpublish'));
		$linkEditElement = $element
			->copy()
			->init('a',
			[
				'href' => $parameterRoute . 'admin/edit/' . $table . '/' . $id,
				'class' => $this->_optionArray['className']['link']['edit'],
				'data-description' => $this->_language->get('edit')
			])
			->text($this->_language->get('edit'));
		$linkDeleteElement = $element
			->copy()
			->init('a',
			[
				'href' => $parameterRoute . 'admin/delete/' . $table . '/' . $id . '/' . $token,
				'class' => $this->_optionArray['className']['link']['delete'],
				'data-description' => $this->_language->get('delete')
			])
			->text($this->_language->get('delete'));

		/* collect output */

		if ($tableEdit || $tableDelete)
		{
			if ($tableEdit)
			{
				$boxElement->append($linkUnpublishElement . $linkEditElement);
			}
			if ($tableDelete)
			{
				$boxElement->append($linkDeleteElement);
			}
			$output .= $wrapperElement->html($boxElement);
		}
		$output .= Module\Hook::trigger('adminDockEnd');
		return $output;
	}
}

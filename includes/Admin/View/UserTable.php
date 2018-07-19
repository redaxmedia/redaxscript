<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the admin user table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class UserTable extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminUserTableStart');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$usersNew = $this->_registry->get('usersNew');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('users'));
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-button-default rs-admin-button-create',
				'href' => $parameterRoute . 'admin/new/users'
			])
			->text($this->_language->get('user_new'));

		/* collect output */

		$output .= $titleElement;
		if ($usersNew)
		{
			$output .= $linkElement;
		}
		$output .= $this->_renderTable();
		$output .= Module\Hook::trigger('adminUserTableEnd');
		return $output;
	}

	/**
	 * render the table
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderTable() : string
	{
		$output = null;
		$outputHead = null;
		$outputBody = null;
		$outputFoot = null;
		$tableArray =
		[
			'name' => $this->_language->get('name'),
			'user' => $this->_language->get('user'),
			'groups' => $this->_language->get('groups')
		];
		$adminControl = new Helper\Control($this->_registry, $this->_language);
		$userModel = new Admin\Model\User();
		$users = $userModel->getAll();
		$usersTotal = $users->count();

		/* html element */

		$element = new Html\Element();
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => 'rs-admin-wrapper-table'
			]);
		$tableElement = $element
			->copy()
			->init('table',
			[
				'class' => 'rs-admin-table-default'
			]);
		$theadElement = $element->copy()->init('thead');
		$tbodyElement = $element->copy()->init('tbody');
		$tfootElement = $element->copy()->init('tfoot');
		$trElement = $element->copy()->init('tr');
		$thElement = $element->copy()->init('th');
		$tdElement = $element->copy()->init('td');

		/* process table */

		foreach ($tableArray as $key => $value)
		{
			$outputHead .= $thElement->copy()->text($value);
			$outputFoot .= $tdElement->copy()->text($value);
		}

		/* process categories */

		if ($usersTotal)
		{
			foreach ($users as $key => $value)
			{
				$groupArray = array_map('intval', explode(',', $value->groups));
				$outputBody .= $trElement
					->copy()
					->addClass(!$value->status ? 'rs-admin-is-disabled' : null)
					->html(
						$tdElement->copy()->html($value->name . $adminControl->render('users', $value->id, $value->alias, $value->status)) .
						$tdElement->copy()->text($value->user) .
						$tdElement->copy()->html($this->_renderGroup($groupArray))
					);
			}
		}
		else
		{
			$outputBody .= $trElement
				->copy()
				->html(
					$tdElement
						->copy()
						->attr('colspan', count($tableArray))
						->text($this->_language->get('user_no'))
				);
		}

		/* collect output */

		$outputHead = $theadElement->html(
			$trElement->html($outputHead)
		);
		$outputBody = $tbodyElement->html($outputBody);
		$outputFoot = $tfootElement->html(
			$trElement->html($outputFoot)
		);
		$output .= $wrapperElement->copy()->html(
			$tableElement->html($outputHead . $outputBody . $outputFoot)
		);
		return $output;
	}

	/**
	 * render the group
	 *
	 * @since 4.0.0
	 *
	 * @param array $groupArray
	 *
	 * @return string
	 */

	protected function _renderGroup(array $groupArray = []) : string
	{
		$output = null;
		$groupModel = new Admin\Model\Group();

		/* html element */

		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => 'rs-admin-link-default'
		]);

		/* process groups */

		foreach ($groupArray as $groupId)
		{
			$output .= $linkElement
				->copy()
				->attr('href', $this->_registry->get('parameterRoute') . 'admin/edit/groups/' . $groupId)
				->text($groupModel->getById($groupId)->name);
		}
		return $output;
	}
}

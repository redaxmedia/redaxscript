<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Dater;
use Redaxscript\Html;
use Redaxscript\Module;
use function count;
use function json_decode;

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
	 * @return string|null
	 */

	protected function _renderTable() : ?string
	{
		$output = null;
		$outputHead = null;
		$outputBody = null;
		$outputFoot = null;
		$tableArray =
		[
			'name' => $this->_language->get('name'),
			'user' => $this->_language->get('user'),
			'session' => $this->_language->get('session'),
			'groups' => $this->_language->get('groups')
		];
		$adminControl = new Helper\Control($this->_registry, $this->_language);
		$adminControl->init();
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
				$outputBody .= $trElement
					->copy()
					->attr('id', 'row-' . $value->id)
					->addClass(!$value->status ? 'rs-admin-is-disabled' : null)
					->html(
						$tdElement->copy()->html($value->name . $adminControl->render('users', $value->id, $value->alias, $value->status)) .
						$tdElement->copy()->text($value->user) .
						$tdElement->copy()->text($this->_renderSession($value->last)) .
						$tdElement->copy()->html($this->_renderGroup($value->groups))
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
	 * render the session
	 *
	 * @since 4.0.0
	 *
	 * @param string $last
	 *
	 * @return string
	 */

	protected function _renderSession(string $last = null) : string
	{
		$daterLast = new Dater();
		$daterLast->init($last);
		$daterNow = new Dater();
		$daterNow->init($this->_registry->get('now'));

		/* handle session */

		if (!$last)
		{
			return $this->_language->get('session_no');
		}
		if ($daterLast->getDateTime() > $daterNow->getDateTime()->modify('-1 minute'))
		{
			return $this->_language->get('online');
		}
		if ($daterLast->getDateTime() > $daterNow->getDateTime()->modify('+1 minute -1 day'))
		{
			return $this->_language->get('today') . ' ' . $this->_language->get('at') . ' ' . $daterLast->formatTime();
		}
		return $daterLast->formatDate();
	}

	/**
	 * render the group
	 *
	 * @since 4.0.0
	 *
	 * @param string groups
	 *
	 * @return string|null
	 */

	protected function _renderGroup(string $groups = null) : ?string
	{
		$output = null;
		$groupModel = new Admin\Model\Group();
		$groupArray = (array)json_decode($groups);

		/* html element */

		$linkElement = new Html\Element();
		$linkElement->init('a');

		/* process groups */

		if ($groupArray)
		{
			foreach ($groupArray as $groupId)
			{
				$output .= $linkElement
					->copy()
					->attr('href', $this->_registry->get('parameterRoute') . 'admin/edit/groups/' . $groupId)
					->text($groupModel->getById($groupId)->name);
			}
		}
		else
		{
			$output = $this->_language->get('none');
		}
		return $output;
	}
}

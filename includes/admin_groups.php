<?php

/**
 * admin groups list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_groups_list()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* query groups */

	$result = Redaxscript\Db::forTablePrefix('groups')->orderByAsc('name')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="admin-title-content">' . l('groups') . '</h2>';
	$output .= '<div class="admin-wrapper-button-admin">';
	if (GROUPS_NEW == 1)
	{
		$output .= anchor_element('internal', '', 'button_admin button_plus_admin', l('group_new'), 'admin/new/groups');
	}
	$output .= '</div><div class="admin-wrapper-table-admin"><table class="admin-table admin-table-admin">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="admin-s4o6 admin-column-first">' . l('name') . '</th><th class="admin-s1o6 admin-column-second">' . l('alias') . '</th><th class="admin-s1o6 admin-column-last">' . l('filter') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="admin-column-first">' . l('name') . '</td><td class="admin-column-second">' . l('alias') . '</td><td class="admin-column-last">' . l('filter') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l('group_no') . l('point');
	}
	else if ($result)
	{
		$output .= '<tbody>';
		foreach ($result as $r)
		{
			if ($r)
			{
				foreach ($r as $key => $value)
				{
					$$key = stripslashes($value);
				}
			}

			/* build class string */

			if ($status == 1)
			{
				$class_status = '';
			}
			else
			{
				$class_status = 'row_disabled';
			}

			/* collect table row */

			$output .= '<tr';
			if ($alias)
			{
				$output .= ' id="' . $alias . '"';
			}
			if ($class_status)
			{
				$output .= ' class="' . $class_status . '"';
			}
			$output .= '><td class="admin-column-first">' . $name;

			/* collect control output */

			$output .= admin_control('access', 'groups', $id, $alias, $status, GROUPS_NEW, GROUPS_EDIT, GROUPS_DELETE);

			/* collect alias and filter output */

			$output .= '</td><td class="admin-column-second">' . $alias . '</td><td class="admin-column-last">' . $filter . '</td></tr>';
		}
		$output .= '</tbody>';
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}

/**
 * admin groups form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_groups_form()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* define fields for existing group */

	if (ADMIN_PARAMETER == 'edit' && ID_PARAMETER)
	{
		/* query user */

		$result = Redaxscript\Db::forTablePrefix('groups')->where('id', ID_PARAMETER)->findArray();
		$r = $result[0];
		if ($r)
		{
			foreach ($r as $key => $value)
			{
				$$key = stripslashes($value);
			}
		}
		$wording_headline = $name;
		$wording_submit = l('save');
		$route = 'admin/process/groups/' . $id;
	}

	/* else define fields for new group */

	else if (ADMIN_PARAMETER == 'new')
	{
		$categories = 0;
		$articles = 0;
		$extras = 0;
		$comments = 0;
		$groups = 0;
		$users = 0;
		$modules = 0;
		$settings = 0;
		$filter = 1;
		$status = 1;
		$wording_headline = l('group_new');
		$wording_submit = l('create');
		$route = 'admin/process/groups';
	}
	$access_array = array(
		l('create') => 1,
		l('edit') => 2,
		l('delete') => 3
	);
	$modules_access_array = array(
		l('install') => 1,
		l('edit') => 2,
		l('uninstall') => 3
	);

	/* collect output */

	$output .= '<h2 class="admin-title-content">' . $wording_headline . '</h2>';
	$output .= form_element('form', 'form_admin', 'admin-js-validate-form admin-js-tab admin-form-admin admin-hidden-legend', '', '', '', 'action="' . REWRITE_ROUTE . $route . '" method="post"');

	/* collect tab list output */

	$output .= '<ul class="admin-js-list-tab admin-list-tab admin-list-tab-admin">';
	$output .= '<li class="admin-js-item-active admin-item-first admin-item-active">' . anchor_element('internal', '', '', l('group'), FULL_ROUTE . '#tab-1') . '</li>';
	if ($id == '' || $id > 1)
	{
		$output .= '<li class="admin-item-second">' . anchor_element('internal', '', '', l('access'), FULL_ROUTE . '#tab-2') . '</li>';
		$output .= '<li class="admin-item-last">' . anchor_element('internal', '', '', l('customize'), FULL_ROUTE . '#tab-3') . '</li>';
	}
	$output .= '</ul>';

	/* collect tab box output */

	$output .= '<div class="admin-js-box-tab admin-box-tab admin-box-tab-admin">';

	/* collect group set */

	$output .= form_element('fieldset', 'tab-1', 'admin-js-set-tab admin-js-set-active admin-set-tab admin-set-tab-admin admin-set-active', '', '', l('group')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'admin-js-generate-alias-input admin-field-text-admin admin-field-note', 'name', $name, l('name'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	$output .= '<li>' . form_element('text', 'alias', 'admin-js-generate-alias-output admin-field-text-admin admin-field-note', 'alias', $alias, l('alias'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'admin-js-auto-resize admin-field-textarea-admin admin-field-small', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '</ul></fieldset>';
	if ($id == '' || $id > 1)
	{
		/* collect access set */

		$output .= form_element('fieldset', 'tab-2', 'admin-js-set-tab admin-set-tab admin-set-tab-admin', '', '', l('acccess')) . '<ul>';
		$output .= '<li>' . select_element('categories', 'field_select_admin', 'categories', $access_array, $categories, l('categories'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('articles', 'field_select_admin', 'articles', $access_array, $articles, l('articles'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('extras', 'field_select_admin', 'extras', $access_array, $extras, l('extras'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('comments', 'field_select_admin', 'comments', $access_array, $comments, l('comments'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('groups', 'field_select_admin', 'groups', $access_array, $groups, l('groups'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('users', 'field_select_admin', 'users', $access_array, $users, l('users'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('modules', 'field_select_admin', 'modules', $modules_access_array, $modules, l('modules'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('settings', 'field_select_admin', 'settings', array(
			l('none') => 0,
			l('edit') => 1
		), $settings, l('settings')) . '</li>';
		$output .= '</ul></fieldset>';

		/* collect customize set */

		$output .= form_element('fieldset', 'tab-3', 'admin-js-set-tab admin-set-tab admin-set-tab-admin', '', '', l('customize')) . '<ul>';
		$output .= '<li>' . select_element('filter', 'field_select_admin', 'filter', array(
			l('enable') => 1,
			l('disable') => 0
		), $filter, l('filter')) . '</li>';
		$output .= '<li>' . select_element('status', 'field_select_admin', 'status', array(
			l('enable') => 1,
			l('disable') => 0
		), $status, l('status')) . '</li>';
		$output .= '</ul></fieldset>';
	}
	$output .= '</div>';

	/* collect hidden output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);

	/* cancel button */

	if (GROUPS_EDIT == 1 || GROUPS_DELETE == 1)
	{
		$cancel_route = 'admin/view/groups';
	}
	else
	{
		$cancel_route = 'admin';
	}
	$output .= anchor_element('internal', '', 'admin-js-cancel admin-button-admin admin-button-large admin-button-cancel-admin', l('cancel'), $cancel_route);

	/* delete button */

	if (GROUPS_DELETE == 1 && $id > 1)
	{
		$output .= anchor_element('internal', '', 'admin-js-delete admin-js-confirm admin-button-admin admin-button-large admin-button-delete-admin', l('delete'), 'admin/delete/groups/' . $id . '/' . TOKEN);
	}

	/* submit button */

	if (GROUPS_NEW == 1 || GROUPS_EDIT == 1)
	{
		$output .= form_element('button', '', 'admin-js-submit admin-button-admin admin-button-large admin-button-submit-admin', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}
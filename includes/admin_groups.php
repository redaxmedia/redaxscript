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

	$query = 'SELECT id, name, alias, filter, status FROM ' . PREFIX . 'groups';
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);

	/* collect listing output */

	$output .= '<h2 class="title_content">' . l('groups') . '</h2>';
	$output .= '<div class="wrapper_button_admin">';
	if (GROUPS_NEW == 1)
	{
		$output .= anchor_element('internal', '', 'button_admin button_plus_admin', l('group_new'), 'admin/new/groups');
	}
	$output .= '</div><div class="wrapper_table_admin"><table class="table table_admin">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="s4o6 column_first">' . l('name') . '</th><th class="s1o6 column_second">' . l('alias') . '</th><th class="s1o6 column_last">' . l('filter') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="column_first">' . l('name') . '</td><td class="column_second">' . l('alias') . '</td><td class="column_last">' . l('filter') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l('group_no') . l('point');
	}
	else if ($result)
	{
		$output .= '<tbody>';
		while ($r = mysql_fetch_assoc($result))
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
			$output .= '><td class="column_first">' . $name;

			/* collect control output */

			$output .= admin_control('access', 'groups', $id, $alias, $status, GROUPS_NEW, GROUPS_EDIT, GROUPS_DELETE);

			/* collect alias and filter output */

			$output .= '</td><td class="column_second">' . $alias . '</td><td class="column_last">' . $filter . '</td></tr>';
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

		$query = 'SELECT * FROM ' . PREFIX . 'groups WHERE id = ' . ID_PARAMETER;
		$result = mysql_query($query);
		$r = mysql_fetch_assoc($result);
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

	$output .= '<h2 class="title_content">' . $wording_headline . '</h2>';
	$output .= form_element('form', 'form_admin', 'js_validate_form js_tab form_admin hidden_legend', '', '', '', 'action="' . REWRITE_ROUTE . $route . '" method="post"');

	/* collect tab list output */

	$output .= '<ul class="js_list_tab list_tab list_tab_admin">';
	$output .= '<li class="js_item_active item_first item_active">' . anchor_element('internal', '', '', l('group'), FULL_ROUTE . '#tab-1') . '</li>';
	if ($id == '' || $id > 1)
	{
		$output .= '<li class="item_second">' . anchor_element('internal', '', '', l('access'), FULL_ROUTE . '#tab-2') . '</li>';
		$output .= '<li class="item_last">' . anchor_element('internal', '', '', l('customize'), FULL_ROUTE . '#tab-3') . '</li>';
	}
	$output .= '</ul>';

	/* collect tab box output */

	$output .= '<div class="js_box_tab box_tab box_tab_admin">';

	/* collect group set */

	$output .= form_element('fieldset', 'tab-1', 'js_set_tab js_set_active set_tab set_tab_admin set_active', '', '', l('group')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'js_generate_alias_input field_text_admin field_note', 'name', $name, l('name'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	$output .= '<li>' . form_element('text', 'alias', 'js_generate_alias_output field_text_admin field_note', 'alias', $alias, l('alias'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'js_auto_resize field_textarea_admin field_small_admin', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '</ul></fieldset>';
	if ($id == '' || $id > 1)
	{
		/* collect access set */

		$output .= form_element('fieldset', 'tab-2', 'js_set_tab set_tab set_tab_admin', '', '', l('acccess')) . '<ul>';
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

		$output .= form_element('fieldset', 'tab-3', 'js_set_tab set_tab set_tab_admin', '', '', l('customize')) . '<ul>';
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
	$output .= anchor_element('internal', '', 'js_cancel button_admin button_large_admin button_cancel_admin', l('cancel'), $cancel_route);

	/* delete button */

	if (GROUPS_DELETE == 1 && $id > 1)
	{
		$output .= anchor_element('internal', '', 'js_delete js_confirm button_admin button_large_admin button_delete_admin', l('delete'), 'admin/delete/groups/' . $id . '/' . TOKEN);
	}

	/* submit button */

	if (GROUPS_NEW == 1 || GROUPS_EDIT == 1)
	{
		$output .= form_element('button', '', 'js_submit button_admin button_large_admin button_submit_admin', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}
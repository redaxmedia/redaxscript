<?php

/* admin groups list */

function admin_groups_list()
{
	hook(__FUNCTION__ . '_start');

	/* query groups */

	$query = 'SELECT id, name, alias, filter, status FROM ' . PREFIX . 'groups';
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);

	/* collect listing output */

	$output = '<h2 class="title_content">' . l('groups') . '</h2>';
	if (GROUPS_NEW == 1)
	{
		$output .= '<a class="field_button_admin field_button_plus" href="' . REWRITE_STRING . 'admin/new/groups"><span><span>' . l('group_new') . '</span></span></a>';
	}
	$output .= '<div class="wrapper_table_admin"><table class="table table_admin">';
	$output .= '<thead><tr><th class="s2o3 column_first">' . l('name') . '</th><th class="s1o6 column_second">' . l('alias') . '</th><th class="s1o6 column_last">' . l('filter') . '</th></tr></thead>';
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
			if ($class_status)
			{
				$output .= ' class="' . $class_status . '"';
			}
			$output .= '><td class="column_first">' . $name;

			/* collect control output */

			if (GROUPS_EDIT == 1 || (GROUPS_DELETE == 1 && $id > 1))
			{
				$output .= '<ul class="list_control_admin">';
			}
			if (GROUPS_EDIT == 1)
			{
				if ($id > 1)
				{
					if ($status == 1)
					{
						$output .= '<li class="item_disable">' . anchor_element('internal', '', '', l('disable'), 'admin/disable/groups/' . $id . '/' . TOKEN) . '</li>';
					}
					else if ($status == 0)
					{
						$output .= '<li class="item_enable">' . anchor_element('internal', '', '', l('enable'), 'admin/enable/groups/' . $id . '/' . TOKEN) . '</li>';
					}
				}
				$output .= '<li class="item_edit">' . anchor_element('internal', '', '', l('edit'), 'admin/edit/groups/' . $id) . '</li>';
			}
			if (GROUPS_DELETE == 1 && $id > 1)
			{
				$output .= '<li class="item_delete">' . anchor_element('internal', '', 'js_confirm', l('delete'), 'admin/delete/groups/' . $id . '/' . TOKEN) . '</li>';
			}
			if (GROUPS_EDIT == 1 || (GROUPS_DELETE == 1 && $id > 1))
			{
				$output .= '</ul>';
			}

			/* collect premature output */

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
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* admin groups form */

function admin_groups_form()
{
	hook(__FUNCTION__ . '_start');

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
		$string = 'admin/process/groups/' . $id;
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
		$string = 'admin/process/groups';
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

	$output = '<h2 class="title_content">' . $wording_headline . '</h2>';

	/* collect tab menue output */

	$output .= '<ul class="js_list_tab_menue list_tab_menue list_tab_menue_admin">';
	$output .= '<li class="js_item_active item_active item_first">' . anchor_element('internal', '', '', l('group'), FULL_STRING . '#tab-1') . '</li>';
	if ($id == '' || $id > 1)
	{
		$output .= '<li class="item_second">' . anchor_element('internal', '', '', l('access'), FULL_STRING . '#tab-2') . '</li>';
		$output .= '<li class="item_last">' . anchor_element('internal', '', '', l('customize'), FULL_STRING . '#tab-3') . '</li>';
	}
	$output .= '</ul>';

	/* collect tab box output */

	$output .= form_element('form', 'form_admin', 'js_check_required js_note_required form_admin hidden_legend', '', '', '', 'action="' . REWRITE_STRING . $string . '" method="post"');
	$output .= '<div class="js_box_tab_menue box_tab_menue box_tab_menue_admin">';

	/* collect group set */

	$output .= form_element('fieldset', 'tab-1', 'js_set_tab_menue set_tab_menue set_tab_menue_admin', '', '', l('group')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'js_required js_generate_alias_input field_text_admin field_note', 'name', $name, l('name'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	$output .= '<li>' . form_element('text', 'alias', 'js_required js_generate_alias_output field_text_admin field_note', 'alias', $alias, l('alias'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'js_auto_resize field_textarea_small_admin', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '</ul></fieldset>';
	if ($id == '' || $id > 1)
	{
		/* collect access set */

		$output .= form_element('fieldset', 'tab-2', 'js_set_tab_menue set_tab_menue set_tab_menue_admin', '', '', l('acccess')) . '<ul>';
		$output .= '<li>' . select_element('categories', 'field_select_admin field_multiple', 'categories', $access_array, $categories, l('categories'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('articles', 'field_select_admin field_multiple', 'articles', $access_array, $articles, l('articles'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('extras', 'field_select_admin field_multiple', 'extras', $access_array, $extras, l('extras'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('comments', 'field_select_admin field_multiple', 'comments', $access_array, $comments, l('comments'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('groups', 'field_select_admin field_multiple', 'groups', $access_array, $groups, l('groups'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('users', 'field_select_admin field_multiple', 'users', $access_array, $users, l('users'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('modules', 'field_select_admin field_multiple', 'modules', $modules_access_array, $modules, l('modules'), 'multiple="multiple"') . '</li>';
		$output .= '<li>' . select_element('settings', 'field_select_admin', 'settings', array(
			l('none') => 0,
			l('edit') => 1
		), $settings, l('settings')) . '</li>';
		$output .= '</ul></fieldset>';

		/* collect customize set */

		$output .= form_element('fieldset', 'tab-3', 'js_set_tab_menue set_tab_menue set_tab_menue_admin', '', '', l('customize')) . '<ul>';
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

	/* collect premature output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);

	/* cancel button */

	if (GROUPS_EDIT == 1 || GROUPS_DELETE == 1)
	{
		$cancel_string = 'admin/view/groups';
	}
	else
	{
		$cancel_string = 'admin';
	}
	$output .= '<a class="js_cancel field_button_large_admin field_button_backward" href="' . REWRITE_STRING . $cancel_string . '"><span><span>' . l('cancel') . '</span></span></a>';

	/* delete button */

	if (GROUPS_DELETE == 1 && $id > 1)
	{
		$output .= '<a class="js_delete js_confirm field_button_large_admin" href="' . REWRITE_STRING . 'admin/delete/groups/' . $id . '/' . TOKEN . '"><span><span>' . l('delete') . '</span></span></a>';
	}

	/* submit button */

	if (GROUPS_NEW == 1 || GROUPS_EDIT == 1)
	{
		$output .= form_element('button', '', 'js_submit field_button_large_admin field_button_forward', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}
?>
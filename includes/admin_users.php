<?php

/**
 * admin users list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_users_list()
{
	hook(__FUNCTION__ . '_start');

	/* query users */

	$query = 'SELECT id, name, user, language, first, last, status, groups FROM ' . PREFIX . 'users ORDER BY last DESC';
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);

	/* collect listing output */

	$output = '<h2 class="title_content">' . l('users') . '</h2>';
	$output .= '<div class="wrapper_button_admin">';
	if (USERS_NEW == 1)
	{
		$output .= anchor_element('internal', '', 'button_admin button_plus_admin', l('user_new'), 'admin/new/users');
	}
	$output .= '</div><div class="wrapper_table_admin"><table class="table table_admin">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="s3o6 column_first">' . l('name') . '</th><th class="s1o6 column_second">' . l('user') . '</th><td class="s1o6 column_third">' . l('groups') . '</td><th class="s1o6 column_last">' . l('session') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="column_first">' . l('name') . '</td><td class="column_second">' . l('user') . '</td><td class="column_third">' . l('groups') . '</td><td class="column_last">' . l('session') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l('user_no') . l('point');
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
			if ($class_status)
			{
				$output .= ' class="' . $class_status . '"';
			}
			$output .= '><td class="column_first">';
			if ($language)
			{
				$output .= '<span class="icon_flag language_' . $language . '" title="' . l($language) . '">' . $language . '</span>';
			}
			$output .= $name;

			/* collect control output */

			$output .= admin_control('access', 'users', $id, $alias, $status, USERS_NEW, USERS_EDIT, USERS_DELETE);

			/* collect user and parent output */

			$output .= '</td><td class="column_second">' . $user . '</td><td class="column_third">';
			if ($groups)
			{
				$groups_array = explode(', ', $groups);
				$groups_array_keys = array_keys($groups_array);
				$groups_array_last = end($groups_array_keys);
				foreach ($groups_array as $key => $value)
				{
					$group_alias = retrieve('alias', 'groups', 'id', $value);
					if ($group_alias)
					{
						$output .= anchor_element('internal', '', 'link_parent', retrieve('name', 'groups', 'id', $value), 'admin/edit/groups/' . $value);
						if ($groups_array_last != $key)
						{
							$output .= ', ';
						}
					}
				}
			}
			else
			{
				$output .= l('none');
			}
			$output .= '</td><td class="column_last">';
			if ($first == $last)
			{
				$output .= l('none');
			}
			else
			{
				$minute_ago = date('Y-m-d H:i:s', strtotime('-1 minute'));
				$day_ago = date('Y-m-d H:i:s', strtotime('-1 day'));
				if ($last > $minute_ago)
				{
					$output .= l('online');
				}
				else if ($last > $day_ago)
				{
					$time = date(s('time'), strtotime($last));
					$output .= l('today') . ' ' . l('at') . ' ' . $time;
				}
				else
				{
					$date = date(s('date'), strtotime($last));
					$output .= $date;
				}
			}
			$output .= '</td></tr>';
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

/**
 * admin users form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_users_form()
{
	hook(__FUNCTION__ . '_start');

	/* define fields for existing user */

	if (ADMIN_PARAMETER == 'edit' && ID_PARAMETER)
	{
		/* query user */

		$query = 'SELECT * FROM ' . PREFIX . 'users WHERE id = ' . ID_PARAMETER;
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
		$route = 'admin/process/users/' . $id;
	}

	/* else define fields for new user */

	else if (ADMIN_PARAMETER == 'new')
	{
		$status = 1;
		$groups = 0;
		$wording_headline = l('user_new');
		$wording_submit = l('create');
		$route = 'admin/process/users';
		$code_required = ' required="required"';
	}

	/* collect output */

	$output = '<h2 class="title_content">' . $wording_headline . '</h2>';
	$output .= form_element('form', 'form_admin', 'js_validate_form js_tab form_admin hidden_legend', '', '', '', 'action="' . REWRITE_ROUTE . $route . '" method="post"');

	/* collect tab list output */

	$output .= '<ul class="js_list_tab list_tab list_tab_admin">';
	$output .= '<li class="js_item_active item_first item_active">' . anchor_element('internal', '', '', l('user'), FULL_ROUTE . '#tab-1') . '</li>';
	$output .= '<li class="item_second">' . anchor_element('internal', '', '', l('customize'), FULL_ROUTE . '#tab-2') . '</li></ul>';

	/* collect tab box output */

	$output .= '<div class="js_box_tab box_tab box_tab_admin">';

	/* collect user set */

	$output .= form_element('fieldset', 'tab-1', 'js_set_tab js_set_active set_tab set_tab_admin set_active', '', '', l('user')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'field_text_admin field_note', 'name', $name, l('name'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	if ($id == '')
	{
		$output .= '<li>' . form_element('text', 'user', 'field_text_admin field_note', 'user', $user, l('user'), 'maxlength="50" required="required"') . '</li>';
	}
	$output .= '<li>' . form_element('password', 'password', 'js_unmask_password field_text_admin field_note', 'password', '', l('password'), 'maxlength="50" autocomplete="off"' . $code_required) . '</li>';
	$output .= '<li>' . form_element('password', 'password_confirm', 'js_unmask_password field_text_admin field_note', 'password_confirm', '', l('password_confirm'), 'maxlength="50" autocomplete="off"' . $code_required) . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'field_text_admin field_note', 'email', $email, l('email'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'js_auto_resize field_textarea_admin field_small_admin', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect customize set */

	$output .= form_element('fieldset', 'tab-2', 'js_set_tab set_tab set_tab_admin', '', '', l('customize')) . '<ul>';

	/* languages directory object */

	$languages_directory = New Redaxscript_Directory('languages', 'misc.php');
	$languages_directory_array = $languages_directory->get();

	/* build languages select */

	$language_array[l('select')] = '';
	foreach ($languages_directory_array as $value)
	{
		$value = substr($value, 0, 2);
		$language_array[l($value, '_index')] = $value;
	}
	$output .= '<li>' . select_element('language', 'field_select_admin', 'language', $language_array, $language, l('language')) . '</li>';
	if ($id == '' || $id > 1)
	{
		$output .= '<li>' . select_element('status', 'field_select_admin', 'status', array(
			l('enable') => 1,
			l('disable') => 0
		), $status, l('status')) . '</li>';

		/* build groups select */

		if (GROUPS_EDIT == 1 && USERS_EDIT == 1)
		{
			$groups_query = 'SELECT * FROM ' . PREFIX . 'groups ORDER BY name ASC';
			$groups_result = mysql_query($groups_query);
			if ($groups_result)
			{
				while ($g = mysql_fetch_assoc($groups_result))
				{
					$groups_array[$g['name']] = $g['id'];
				}
			}
			$output .= '<li>' . select_element('groups', 'field_select_admin', 'groups', $groups_array, $groups, l('groups'), 'multiple="multiple"') . '</li>';
		}
	}
	$output .= '</ul></fieldset></div>';

	/* collect hidden output */

	if ($id)
	{
		$output .= form_element('hidden', '', '', 'user', $user);
	}
	$output .= form_element('hidden', '', '', 'token', TOKEN);

	/* collect button output */

	if (USERS_EDIT == 1 || USERS_DELETE == 1)
	{
		$cancel_route = 'admin/view/users';
	}
	else
	{
		$cancel_route = 'admin';
	}
	$output .= anchor_element('internal', '', 'js_cancel button_admin button_large_admin button_cancel_admin', l('cancel'), $cancel_route);

	/* delete button */

	if ((USERS_DELETE == 1 || USERS_EXCEPTION == 1) && $id > 1)
	{
		$output .= anchor_element('internal', '', 'js_delete js_confirm button_admin button_large_admin button_delete_admin', l('delete'), 'admin/delete/users/' . $id . '/' . TOKEN);
	}

	/* submit button */

	if (USERS_NEW == 1 || USERS_EDIT == 1 || USERS_EXCEPTION == 1)
	{
		$output .= form_element('button', '', 'js_submit button_admin button_large_admin button_submit_admin', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}
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
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* query users */

	$result = Redaxscript\Db::forTablePrefix('users')->orderByDesc('last')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . l('users') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if (USERS_NEW == 1)
	{
		$output .= anchor_element('internal', '', 'rs-admin-button rs-admin-button-plus', l('user_new'), 'admin/new/users');
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table rs-admin-table">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-s3o6 rs-admin-column-first">' . l('name') . '</th><th class="rs-admin-s1o6 rs-admin-column-second">' . l('user') . '</th><th class="rs-admin-s1o6 rs-admin-column-third">' . l('groups') . '</th><th class="rs-admin-s1o6 rs-admin-column-last">' . l('session') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="rs-admin-column-first">' . l('name') . '</td><td class="rs-admin-column-second">' . l('user') . '</td><td class="rs-admin-column-third">' . l('groups') . '</td><td class="rs-admin-column-last">' . l('session') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l('user_no') . l('point');
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
			if ($user)
			{
				$output .= ' id="' . $user . '"';
			}
			if ($class_status)
			{
				$output .= ' class="' . $class_status . '"';
			}
			$output .= '><td class="rs-admin-column-first">';
			if ($language)
			{
				$output .= '<span class="rs-admin-icon-flag rs-admin-language-' . $language . '" title="' . l($language) . '">' . $language . '</span>';
			}
			$output .= $name;

			/* collect control output */

			$output .= admin_control('access', 'users', $id, $alias, $status, USERS_NEW, USERS_EDIT, USERS_DELETE);

			/* collect user and parent output */

			$output .= '</td><td class="rs-admin-column-second">' . $user . '</td><td class="rs-admin-column-third">';
			if ($groups)
			{
				$groups_array = explode(', ', $groups);
				$groups_array_keys = array_keys($groups_array);
				$groups_array_last = end($groups_array_keys);
				foreach ($groups_array as $key => $value)
				{
					$group_alias = Redaxscript\Db::forTablePrefix('groups')->where('id', $value)->findOne()->alias;
					if ($group_alias)
					{
						$group_name = Redaxscript\Db::forTablePrefix('groups')->where('id', $value)->findOne()->name;
						$output .= anchor_element('internal', '', 'link_parent', $group_name, 'admin/edit/groups/' . $value);
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
			$output .= '</td><td class="rs-admin-column-last">';
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
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
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
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* define fields for existing user */

	if (ADMIN_PARAMETER == 'edit' && ID_PARAMETER)
	{
		/* query user */

		$result = Redaxscript\Db::forTablePrefix('users')->where('id', ID_PARAMETER)->findArray();
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

	$output .= '<h2 class="title_content">' . $wording_headline . '</h2>';
	$output .= form_element('form', 'form_admin', 'rs-admin-js-validate-form rs-admin-js-tab rs-admin-form rs-admin-hidden-legend', '', '', '', 'action="' . REWRITE_ROUTE . $route . '" method="post"');

	/* collect tab list output */

	$output .= '<ul class="rs-admin-js-list-tab rs-admin-list-tab rs-admin-list-tab">';
	$output .= '<li class="rs-admin-js-item-active rs-admin-item-first rs-admin-item-active">' . anchor_element('internal', '', '', l('user'), FULL_ROUTE . '#tab-1') . '</li>';
	$output .= '<li class="rs-admin-item-second">' . anchor_element('internal', '', '', l('customize'), FULL_ROUTE . '#tab-2') . '</li></ul>';

	/* collect tab box output */

	$output .= '<div class="rs-admin-js-box-tab rs-admin-box-tab rs-admin-box-tab">';

	/* collect user set */

	$output .= form_element('fieldset', 'tab-1', 'rs-admin-js-set-tab rs-admin-js-set-active rs-admin-set-tab rs-admin-set-tab rs-admin-set-active', '', '', l('user')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'rs-admin-field-text rs-admin-field-note', 'name', $name, l('name'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	if ($id == '')
	{
		$output .= '<li>' . form_element('text', 'user', 'rs-admin-field-text rs-admin-field-note', 'user', $user, l('user'), 'maxlength="50" required="required"') . '</li>';
	}
	$output .= '<li>' . form_element('password', 'password', 'rs-admin-js-unmask-password rs-admin-field-text rs-admin-field-note', 'password', '', l('password'), 'maxlength="50" autocomplete="off"' . $code_required) . '</li>';
	$output .= '<li>' . form_element('password', 'password_confirm', 'rs-admin-js-unmask-password rs-admin-field-text rs-admin-field-note', 'password_confirm', '', l('password_confirm'), 'maxlength="50" autocomplete="off"' . $code_required) . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'rs-admin-field-text rs-admin-field-note', 'email', $email, l('email'), 'maxlength="50" required="required"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'rs-admin-js-auto-resize rs-admin-field-textarea rs-admin-field-small', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect customize set */

	$output .= form_element('fieldset', 'tab-2', 'rs-admin-js-set-tab rs-admin-set-tab rs-admin-set-tab', '', '', l('customize')) . '<ul>';

	/* languages directory object */

	$languages_directory = new Redaxscript\Directory();
	$languages_directory->init('languages');
	$languages_directory_array = $languages_directory->getArray();

	/* build languages select */

	$language_array[l('select')] = '';
	foreach ($languages_directory_array as $value)
	{
		$value = substr($value, 0, 2);
		$language_array[l($value, '_index')] = $value;
	}
	$output .= '<li>' . select_element('language', 'rs-admin-field-select', 'language', $language_array, $language, l('language')) . '</li>';
	if ($id == '' || $id > 1)
	{
		$output .= '<li>' . select_element('status', 'rs-admin-field-select', 'status', array(
			l('enable') => 1,
			l('disable') => 0
		), $status, l('status')) . '</li>';

		/* build groups select */

		if (GROUPS_EDIT == 1 && USERS_EDIT == 1)
		{
			$groups_result = Redaxscript\Db::forTablePrefix('groups')->orderByAsc('name')->findArray();
			if ($groups_result)
			{
				foreach ($groups_result as $g)
				{
					$groups_array[$g['name']] = $g['id'];
				}
			}
			$output .= '<li>' . select_element('groups', 'rs-admin-field-select', 'groups', $groups_array, $groups, l('groups'), 'multiple="multiple"') . '</li>';
		}
	}
	$output .= '</ul></fieldset></div>';

	/* collect hidden output */

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
	$output .= anchor_element('internal', '', 'rs-admin-js-cancel rs-admin-button rs-admin-button-large rs-admin-button-cancel', l('cancel'), $cancel_route);

	/* delete button */

	if ((USERS_DELETE == 1 || USERS_EXCEPTION == 1) && $id > 1)
	{
		$output .= anchor_element('internal', '', 'rs-admin-js-delete rs-admin-js-confirm rs-admin-button rs-admin-button-large rs-admin-button-delete', l('delete'), 'admin/delete/users/' . $id . '/' . TOKEN);
	}

	/* submit button */

	if (USERS_NEW == 1 || USERS_EDIT == 1 || USERS_EXCEPTION == 1)
	{
		$output .= form_element('button', '', 'rs-admin-js-submit rs-admin-button rs-admin-button-large rs-admin-button-submit', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}

<?php

/* admin modules list */

function admin_modules_list()
{
	hook(__FUNCTION__ . '_start');

	/* query modules */

	$query = 'SELECT id, name, alias, version, status FROM ' . PREFIX . 'modules';
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);

	/* collect listing output */

	$output = '<h2 class="title_content">' . l('modules') . '</h2>';
	$output .= '<div class="wrapper_table_admin"><table class="table table_admin">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="s4o6 column_first">' . l('name') . '</th><th class="s1o6 column_second">' . l('alias') . '</th><th class="s1o6 column_last">' . l('version') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="column_first">' . l('name') . '</td><td class="column_second">' . l('alias') . '</td><td class="column_last">' . l('version') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l('module_no') . l('point');
	}
	else if ($result)
	{
		$output .= '<tbody>';
		$modules_installed = array();
		while ($r = mysql_fetch_assoc($result))
		{
			$access = $r['access'];
			$check_access = check_access($access, MY_GROUPS);

			/* if access granted */

			if ($check_access == 1)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}
				$modules_installed[] = $alias;
				$file_install = file_exists('modules/' . $alias . '/install.php');
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

				if (MODULES_EDIT == 1 || MODULES_UNINSTALL == 1)
				{
					$output .= '<ul class="list_control_admin">';
				}
				if (MODULES_EDIT == 1)
				{
					if ($status == 1)
					{
						$output .= '<li class="item_disable">' . anchor_element('internal', '', '', l('disable'), 'admin/disable/modules/' . $id . '/' . TOKEN) . '</li>';
					}
					else if ($status == 0)
					{
						$output .= '<li class="item_enable">' . anchor_element('internal', '', '', l('enable'), 'admin/enable/modules/' . $id . '/' . TOKEN) . '</li>';
					}
					$output .= '<li class="item_edit">' . anchor_element('internal', '', '', l('edit'), 'admin/edit/modules/' . $id) . '</li>';
				}
				if (MODULES_UNINSTALL == 1 && $file_install)
				{
					$output .= '<li class="item_uninstall">' . anchor_element('internal', '', 'js_confirm', l('uninstall'), 'admin/uninstall/modules/' . $alias . '/' . TOKEN) . '</li>';
				}
				if (MODULES_EDIT == 1 || MODULES_UNINSTALL == 1)
				{
					$output .= '</ul>';
				}

				/* collect alias and version output */

				$output .= '</td><td class="column_second">' . $alias . '</td><td class="column_last">' . $version . '</td></tr>';
			}
			else
			{
				$counter++;
			}
		}
		$output .= '</tbody>';

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = l('access_no') . l('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}

	/* modules not installed */

	if (MODULES_INSTALL == 1)
	{
		$modules_directory = read_directory('modules');
		if ($modules_directory && $modules_installed)
		{
			$modules_not_installed = array_diff($modules_directory, $modules_installed);
		}
		else if ($modules_directory)
		{
			$modules_not_installed = $modules_directory;
		}
		if ($modules_not_installed)
		{
			$output .= '<tbody><tr class="row_group"><td colspan="3">' . l('install') . '</td></tr>';
			foreach ($modules_not_installed as $value)
			{
				$file_install = file_exists('modules/' . $value . '/install.php');
				if ($file_install)
				{
					$class_file_install = '';
				}
				else
				{
					$class_file_install = 'row_disabled';
				}

				/* collect table row */

				$output .= '<tr';
				if ($class_file_install)
				{
					$output .= ' class="' . $class_file_install . '"';
				}
				$output .= '><td colspan="3">' . $value;

				/* collect control output */

				if ($file_install)
				{
					$output .= '<ul class="list_control_admin"><li class="item_install">' . anchor_element('internal', '', 'install', l('install'), 'admin/install/modules/' . $value . '/' . TOKEN) . '</li></ul>';
				}
				$output .= '</td></tr>';
			}
			$output .= '</tbody>';
		}
	}
	$output .= '</table></div>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* admin modules form */

function admin_modules_form()
{
	hook(__FUNCTION__ . '_start');

	/* define fields for existing user */

	if (ADMIN_PARAMETER == 'edit' && ID_PARAMETER)
	{
		/* query modules */

		$query = 'SELECT * FROM ' . PREFIX . 'modules WHERE id = ' . ID_PARAMETER;
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
		$string = 'admin/process/modules/' . $id;
	}
	$file_install = file_exists('modules/' . $alias . '/install.php');

	/* collect output */

	$output = '<h2 class="title_content">' . $wording_headline . '</h2>';

	/* collect tab output */

	$output .= '<ul class="js_list_tab list_tab list_tab_admin">';
	$output .= '<li class="js_item_active item_active item_first">' . anchor_element('internal', '', '', l('module'), FULL_STRING . '#tab-1') . '</li>';
	$output .= '<li class="item_second">' . anchor_element('internal', '', '', l('customize'), FULL_STRING . '#tab-2') . '</li></ul>';

	/* collect tab box output */

	$output .= form_element('form', 'form_admin', 'js_check_required js_note_required form_admin hidden_legend', '', '', '', 'action="' . REWRITE_STRING . $string . '" method="post"');
	$output .= '<div class="js_box_tab box_tab box_tab_admin">';

	/* collect module set */

	$output .= form_element('fieldset', 'tab-1', 'js_set_tab set_tab set_tab_admin', '', '', l('user')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'js_required field_text_admin field_note', 'name', $name, l('name'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'js_auto_resize field_textarea_small_admin', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect customize set */

	$output .= form_element('fieldset', 'tab-2', 'js_set_tab set_tab set_tab_admin', '', '', l('customize')) . '<ul>';
	$output .= '<li>' . select_element('status', 'field_select_admin', 'status', array(
		l('enable') => 1,
		l('disable') => 0
	), $status, l('status')) . '</li>';

	/* build access select */

	if (GROUPS_EDIT == 1)
	{
		$access_array[l('all')] = 0;
		$access_query = 'SELECT * FROM ' . PREFIX . 'groups ORDER BY name ASC';
		$access_result = mysql_query($access_query);
		if ($access_result)
		{
			while ($g = mysql_fetch_assoc($access_result))
			{
				$access_array[$g['name']] = $g['id'];
			}
		}
		$output .= '<li>' . select_element('access', 'field_select_admin field_multiple', 'access', $access_array, $access, l('access'), 'multiple="multiple"') . '</li>';
	}
	$output .= '</ul></fieldset></div>';

	/* collect hidden output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);

	/* cancel button */

	if (MODULES_EDIT == 1 || MODULES_UNINSTALL == 1)
	{
		$cancel_string = 'admin/view/modules';
	}
	else
	{
		$cancel_string = 'admin';
	}
	$output .= '<a class="js_cancel field_button_large_admin field_button_backward" href="' . REWRITE_STRING . $cancel_string . '"><span><span>' . l('cancel') . '</span></span></a>';

	/* uninstall button */

	if (MODULES_UNINSTALL == 1 && $file_install)
	{
		$output .= '<a class="js_delete js_confirm field_button_large_admin" href="' . REWRITE_STRING . 'admin/uninstall/modules/' . $alias . '/' . TOKEN . '"><span><span>' . l('uninstall') . '</span></span></a>';
	}

	/* submit button */

	if (MODULES_EDIT == 1)
	{
		$output .= form_element('button', '', 'js_submit field_button_large_admin field_button_forward', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}
?>
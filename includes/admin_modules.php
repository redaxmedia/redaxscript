<?php

/**
 * admin modules list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_modules_list()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* query modules */

	$result = Redaxscript\Db::forTablePrefix('modules')->orderByAsc('name')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="admin-title-content">' . l('modules') . '</h2>';
	$output .= '<div class="admin-wrapper-table-admin"><table class="admin-table admin-table-admin">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="admin-s4o6 admin-column-first">' . l('name') . '</th><th class="admin-s1o6 admin-column-second">' . l('alias') . '</th><th class="admin-s1o6 admin-column-last">' . l('version') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="column-first">' . l('name') . '</td><td class="column-second">' . l('alias') . '</td><td class="column-last">' . l('version') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l('module_no') . l('point');
	}
	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		$output .= '<tbody>';
		foreach ($result as $r)
		{
			$access = $r['access'];

			/* access granted */

			if ($accessValidator->validate($access, MY_GROUPS) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}
				$modules_installed_array[] = $alias;

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
				$output .= '><td class="column-first">' . $name;

				/* collect control output */

				$output .= admin_control('modules_installed', 'modules', $id, $alias, $status, MODULES_INSTALL, MODULES_EDIT, MODULES_UNINSTALL);

				/* collect alias and version output */

				$output .= '</td><td class="column-second">' . $alias . '</td><td class="column-last">' . $version . '</td></tr>';
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
		/* modules directory object */

		$modules_directory = new Redaxscript\Directory();
		$modules_directory->init('modules');
		$modules_directory_array = $modules_directory->getArray();
		if ($modules_directory_array && $modules_installed_array)
		{
			$modules_not_installed_array = array_diff($modules_directory_array, $modules_installed_array);
		}
		else if ($modules_directory_array)
		{
			$modules_not_installed_array = $modules_directory_array;
		}
		if ($modules_not_installed_array)
		{
			$output .= '<tbody><tr class="admin-row-group"><td colspan="3">' . l('install') . '</td></tr>';
			foreach ($modules_not_installed_array as $alias)
			{
				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				$output .= '><td colspan="3">' . $alias;

				/* collect control output */

				$output .= admin_control('modules_not_installed', 'modules', $id, $alias, $status, MODULES_INSTALL, MODULES_EDIT, MODULES_UNINSTALL);
				$output .= '</td></tr>';
			}
			$output .= '</tbody>';
		}
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}

/**
 * admin modules form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_modules_form()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* define fields for existing user */

	if (ADMIN_PARAMETER == 'edit' && ID_PARAMETER)
	{
		/* query modules */

		$result = Redaxscript\Db::forTablePrefix('modules')->where('id', ID_PARAMETER)->findArray();
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
		$route = 'admin/process/modules/' . $id;
	}

	/* directory object */

	$docs_directory = new Redaxscript\Directory();
	$docs_directory ->init('modules/' . $alias . '/docs');
	$docs_directory_array = $docs_directory->getArray();

	/* collect output */

	$output .= '<h2 class="admin-title-content">' . $wording_headline . '</h2>';
	$output .= form_element('form', 'form_admin', 'admin-js-validate-form admin-js-tab admin-form-admin admin-hidden-legend', '', '', '', 'action="' . REWRITE_ROUTE . $route . '" method="post"');

	/* collect tab list output */

	$output .= '<ul class="admin-js-list-tab admin-list-tab admin-list-tab-admin">';
	$output .= '<li class="admin-js-item-active admin-item-first admin-item-active">' . anchor_element('internal', '', '', l('module'), FULL_ROUTE . '#tab-1') . '</li>';
	$output .= '<li class="admin-item-second">' . anchor_element('internal', '', '', l('customize'), FULL_ROUTE . '#tab-2') . '</li>';
	foreach ($docs_directory_array as $key => $value)
	{
		$output .= '<li class="admin-item-third">' . anchor_element('internal', '', '', str_replace('.phtml', '', $value), FULL_ROUTE . '#tab-'. ($key + 3)) . '</li>';
	}
	$output .= '</ul>';

	/* collect tab box output */

	$output .= '<div class="admin-js-box-tab admin-box-tab admin-box-tab-admin">';

	/* collect module set */

	$output .= form_element('fieldset', 'tab-1', 'admin-js-set-tab admin-js-set-active admin-set-tab admin-set-tab-admin admin-set-active', '', '', l('user')) . '<ul>';
	$output .= '<li>' . form_element('text', 'name', 'admin-field-text-admin admin-field-note', 'name', $name, l('name'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
	$output .= '<li>' . form_element('textarea', 'description', 'admin-js-auto-resize admin-field-textarea-admin admin-field-small', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
	$output .= '</ul></fieldset>';

	/* collect customize set */

	$output .= form_element('fieldset', 'tab-2', 'admin-js-set-tab admin-set-tab admin-set-tab-admin', '', '', l('customize')) . '<ul>';
	$output .= '<li>' . select_element('status', 'field_select_admin', 'status', array(
			l('enable') => 1,
			l('disable') => 0
		), $status, l('status')) . '</li>';

	/* build access select */

	if (GROUPS_EDIT == 1)
	{
		$access_array[l('all')] = null;
		$access_result = Redaxscript\Db::forTablePrefix('groups')->orderByAsc('name')->findArray();
		if ($access_result)
		{
			foreach ($access_result as $g)
			{
				$access_array[$g['name']] = $g['id'];
			}
		}
		$output .= '<li>' . select_element('access', 'field_select_admin', 'access', $access_array, $access, l('access'), 'multiple="multiple"') . '</li></ul></fieldset>';
	}

	/* template object */

	$template = new Redaxscript\Template;

	/* collect docs set */

	foreach ($docs_directory_array as $key => $value)
	{
		$output .= form_element('fieldset', 'tab-' . ($key + 3), 'admin-js-set-tab admin-set-tab admin-set-tab-admin', '', '', 'docs') . '<ul>';
		$output .= '<li>' . $template->partial('modules/' . $alias . '/docs/' . $value) . '</li></ul></fieldset>';
	}
	$output .= '</div>';

	/* collect hidden output */

	$output .= form_element('hidden', '', '', 'alias', $alias);
	$output .= form_element('hidden', '', '', 'token', TOKEN);

	/* cancel button */

	if (MODULES_EDIT == 1 || MODULES_UNINSTALL == 1)
	{
		$cancel_route = 'admin/view/modules';
	}
	else
	{
		$cancel_route = 'admin';
	}
	$output .= anchor_element('internal', '', 'admin-js-cancel admin-button-admin admin-button-large admin-button-cancel-admin', l('cancel'), $cancel_route);

	/* uninstall button */

	if (MODULES_UNINSTALL == 1)
	{
		$output .= anchor_element('internal', '', 'admin-js-delete admin-js-confirm admin-button-admin admin-button-large admin-button-uninstall-admin', l('uninstall'), 'admin/uninstall/modules/' . $alias . '/' . TOKEN);
	}

	/* submit button */

	if (MODULES_EDIT == 1)
	{
		$output .= form_element('button', '', 'admin-js-submit admin-button-admin admin-button-large admin-button-submit-admin', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}

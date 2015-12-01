<?php

/**
 * router
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Center
 * @author Henry Ruhs
 */

function router()
{
	Redaxscript\Hook::trigger('routerStart');
	if (Redaxscript\Registry::get('routerBreak') == 1)
	{
		return;
	}

	/* check token */

	if ($_POST && $_POST['token'] != TOKEN)
	{
		notification(l('error_occurred'), l('token_incorrect'), l('home'), ROOT);
		return;
	}

	/* call default post */

	$post_list = array(
		'Redaxscript\View\Login' => 'login_post',
		'Redaxscript\View\Register' => 'registration_post',
		'Redaxscript\View\Reset' => 'password_reset',
		'comment',
		'reminder',
		'search'
	);
	foreach ($post_list as $key => $value)
	{
		if ($_POST[$value . '_post'] && function_exists($value . '_post'))
		{
			call_user_func($value . '_post');
			return;
		}
		if ($_POST[$key] && function_exists($value))
		{
			call_user_func($value);
			return;
		}
	}

	/* general routing */

	switch (FIRST_PARAMETER)
	{
		case 'admin':
			if (LOGGED_IN == TOKEN)
			{
				admin_router();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('login'), 'login');
			}
			return;
		case 'login':
			$login = new Redaxscript\View\Login();
			echo $login->render();
			return;
		case 'logout':
			if (LOGGED_IN == TOKEN)
			{
				logout();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('login'), 'login');
			}
			return;
		case 'reset':
			if (s('reminder') == 1 && FIRST_SUB_PARAMETER && THIRD_PARAMETER)
			{
				password_reset_form();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('home'), ROOT);
			}
			return;
		case 'register':
			if (s('registration'))
			{
				$register = new Redaxscript\View\Register();
				echo $register->render();
				return;
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('home'), ROOT);
			}
			return;
		case 'recover':
			if (s('reminder') == 1)
			{
				reminder_form();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('home'), ROOT);
			}
			return;
		default:
			contents();
			return;
	}
	Redaxscript\Hook::trigger('routerEnd');
}

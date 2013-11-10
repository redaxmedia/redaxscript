<?php

/**
 * center
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Center
 * @author Henry Ruhs
 */

function center()
{
	hook(__FUNCTION__ . '_start');

	/* center break */

	if (CENTER_BREAK == 1)
	{
		return;
		break;
	}

	/* else routing */

	else
	{
		routing();
	}
	hook(__FUNCTION__ . '_end');
}

/**
 * routing
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Center
 * @author Henry Ruhs
 */

function routing()
{
	/* check token */

	if ($_POST && $_POST['token'] != TOKEN)
	{
		notification(l('error_occurred'), l('token_incorrect'), l('home'), ROOT);
		return;
		break;
	}

	/* call default post */

	$post_list = explode(', ', b('default_post'));
	foreach ($post_list as $value)
	{
		if ($_POST[$value . '_post'] && function_exists($value . '_post'))
		{
			call_user_func($value . '_post');
			return;
			break;
		}
	}

	/* general routing */

	switch (FIRST_PARAMETER)
	{
		case 'admin':
			if (LOGGED_IN == TOKEN)
			{
				admin_routing();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('login'), 'login');
			}
			return;
			break;
		case 'login':
			login_form();
			return;
			break;
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
			break;
		case 'password_reset':
			if (s('reminder') == 1 && FIRST_SUB_PARAMETER && THIRD_PARAMETER)
			{
				password_reset_form();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('home'), ROOT);
			}
			return;
			break;
		case 'registration':
			if (s('registration'))
			{
				registration_form();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('home'), ROOT);
			}
			return;
			break;
		case 'reminder':
			if (s('reminder') == 1)
			{
				reminder_form();
			}
			else
			{
				notification(l('error_occurred'), l('access_no'), l('home'), ROOT);
			}
			return;
			break;
		default:
			contents();
			return;
			break;
	}
}
?>
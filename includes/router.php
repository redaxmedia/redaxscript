<?php
use Redaxscript\Language;

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
	$firstParameter = Redaxscript\Registry::get('firstParameter');
	$secondParameter = Redaxscript\Registry::get('secondParameter');
	$thirdParameter = Redaxscript\Registry::get('thirdParameter');
	$thirdSubParameter = Redaxscript\Registry::get('thirdSubParameter');
	Redaxscript\Hook::trigger('routerStart');
	if (Redaxscript\Registry::get('routerBreak') == 1)
	{
		return;
	}

	/* check token */

	$messenger = new Redaxscript\Messenger();
	if ($_POST && $_POST['token'] != Redaxscript\Registry::get('token'))
	{
		echo $messenger->setAction(Redaxscript\Language::get('home'), Redaxscript\Registry::get('root'))->error(Redaxscript\Language::get('token_incorrect'), Redaxscript\Language::get('error_occurred'));
		return;
	}

	/* call default post */

	$post_list = array(
		'Redaxscript\View\LoginForm' => 'login_post',
		'Redaxscript\View\RegisterForm' => 'Redaxscript\Controller\RegisterPost',
		'Redaxscript\View\ResetForm' => 'Redaxscript\Controller\ResetPost',
		'Redaxscript\View\RecoverForm' => 'Redaxscript\Controller\RecoverPost',
		'Redaxscript\View\CommentForm' => 'Redaxscript\Controller\CommentPost',
		'Redaxscript\View\SearchForm' => 'search_post'
	);
	foreach ($post_list as $key => $value)
	{
		if ($_POST[$key] && function_exists($value))
		{
			call_user_func($value);
			return;
		}
		elseif ($_POST[$key])
		{
			$controller  = new $value(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance(), Redaxscript\Request::getInstance());
			echo $controller ->process();
			return;
		}
	}

	/* general routing */

	switch ($firstParameter)
	{
		case 'admin':
			if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
			{
				admin_router();
			}
			else
			{
				echo $messenger->setAction(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
			}
			return;
		case 'login':
			switch ($secondParameter)
			{
			case 'recover':
				if (Redaxscript\Db::getSetting('recovery') == 1)
				{
					$recoverForm = new Redaxscript\View\RecoverForm();
					echo $recoverForm->render();
					return;
				}
			case 'reset':
				if (Redaxscript\Db::getSetting('recovery') == 1 && $thirdParameter && $thirdSubParameter)
				{
					$resetForm = new Redaxscript\View\ResetForm();
					echo $resetForm->render();
					return;
				}

				/* show error */

				echo $messenger->setAction(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
				return;
			default:
				$loginForm = new Redaxscript\View\LoginForm();
				echo $loginForm->render();
				return;
			}
		case 'logout':
			if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
			{
				logout();
				return;
			}

			/* show error */

			echo $messenger->setAction(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
			return;
		case 'register':
			if (Redaxscript\Db::getSetting('registration'))
			{
				$registerForm = new Redaxscript\View\RegisterForm();
				echo $registerForm->render();
				return;
			}

			/* show error */

			echo $messenger->setAction(Language::get('home'), Redaxscript\Registry::get('root'))->error(Language::get('access_no'), Language::get('error_occurred'));
			return;
		default:
			contents();
			return;
	}
	Redaxscript\Hook::trigger('routerEnd');
}

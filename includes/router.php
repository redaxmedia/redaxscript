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
	if (Redaxscript\Registry::get('routerBreak'))
	{
		return;
	}

	/* check token */

	$messenger = new Redaxscript\Messenger(Redaxscript\Registry::getInstance());
	if ($_POST && $_POST['token'] != Redaxscript\Registry::get('token'))
	{
		echo $messenger->setRoute(Redaxscript\Language::get('home'), Redaxscript\Registry::get('root'))->error(Redaxscript\Language::get('token_incorrect'), Redaxscript\Language::get('error_occurred'));
		return;
	}

	/* install routing */

	if (Redaxscript\Registry::get('file') === 'install.php')
	{
		if (Redaxscript\Request::getPost('Redaxscript\View\InstallForm'))
		{
			$installController = new Redaxscript\Controller\Install(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance(), Redaxscript\Request::getInstance(), Redaxscript\Config::getInstance());
			echo $installController->process();
			return;
		}
		else
		{
			$systemStatus = new Redaxscript\View\SystemStatus(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
			$installForm = new Redaxscript\View\InstallForm(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
			echo $systemStatus->render() . $installForm->render();
			return;
		}
	}

	/* general routing */

	$post_list = array(
		'Redaxscript\View\LoginForm' => 'Redaxscript\Controller\Login',
		'Redaxscript\View\RegisterForm' => 'Redaxscript\Controller\Register',
		'Redaxscript\View\ResetForm' => 'Redaxscript\Controller\Reset',
		'Redaxscript\View\RecoverForm' => 'Redaxscript\Controller\Recover',
		'Redaxscript\View\CommentForm' => 'Redaxscript\Controller\Comment'
	);
	foreach ($post_list as $key => $value)
	{
		if (Redaxscript\Request::getPost($key))
		{
			if (class_exists($value))
			{
				$controller = new $value(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance(), Redaxscript\Request::getInstance());
				echo $controller->process();
			}
			return;
		}
	}

	/* search routing */

	if (Redaxscript\Request::getPost('Redaxscript\View\SearchForm'))
	{
		$messenger = new Redaxscript\Messenger(Redaxscript\Registry::getInstance());
		$table = Redaxscript\Request::getPost('table');
		if($table)
		{
			$table = '/' . $table;
		}
		echo $messenger->setRoute(Redaxscript\Language::get('continue'), 'search' . $table  . '/' . Redaxscript\Request::getPost('search'))->doRedirect(0)->success(Redaxscript\Language::get('search'));
	}

	/* parameter routing */

	switch ($firstParameter)
	{
		case 'admin':
			if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
			{
				admin_router();
			}
			else
			{
				echo $messenger->setRoute(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
			}
			return;
		case 'login':
			switch ($secondParameter)
			{
			case 'recover':
				if (Redaxscript\Db::getSetting('recovery') == 1)
				{
					$recoverForm = new Redaxscript\View\RecoverForm(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
					echo $recoverForm->render();
					return;
				}
			case 'reset':
				if (Redaxscript\Db::getSetting('recovery') == 1 && $thirdParameter && $thirdSubParameter)
				{
					$resetForm = new Redaxscript\View\ResetForm(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
					echo $resetForm->render();
					return;
				}

				/* show error */

				echo $messenger->setRoute(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
				return;
			default:
				$loginForm = new Redaxscript\View\LoginForm(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
				echo $loginForm->render();
				return;
			}
		case 'logout':
			if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
			{
				$logoutController = new Redaxscript\Controller\Logout(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance(), Redaxscript\Request::getInstance());
				echo $logoutController->process();
				return;
			}

			/* show error */

			echo $messenger->setRoute(Language::get('login'), 'login')->error(Language::get('access_no'), Language::get('error_occurred'));
			return;
		case 'register':
			if (Redaxscript\Db::getSetting('registration'))
			{
				$registerForm = new Redaxscript\View\RegisterForm(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
				echo $registerForm->render();
				return;
			}

			/* show error */

			echo $messenger->setRoute(Language::get('home'), Redaxscript\Registry::get('root'))->error(Language::get('access_no'), Language::get('error_occurred'));
			return;
		case 'search':
			$searchController = new Redaxscript\Controller\Search(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance(), Redaxscript\Request::getInstance());
			echo $searchController->process();
			return;
		default:
			contents();
			return;
	}
	Redaxscript\Hook::trigger('routerEnd');
}

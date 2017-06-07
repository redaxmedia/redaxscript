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
	$registry = Redaxscript\Registry::getInstance();
	$request = Redaxscript\Request::getInstance();
	$language = Redaxscript\Language::getInstance();
	$config = Redaxscript\Config::getInstance();
	$firstParameter = $registry->get('firstParameter');
	$secondParameter = $registry->get('secondParameter');
	$thirdParameter = $registry->get('thirdParameter');
	$thirdSubParameter = $registry->get('thirdSubParameter');
	$config = Redaxscript\Config::getInstance();
	Redaxscript\Module\Hook::trigger('routerStart');
	if ($registry->get('routerBreak'))
	{
		return;
	}

	/* check token */

	$messenger = new Redaxscript\Messenger($registry);
	if ($_POST && $_POST['token'] != $registry->get('token'))
	{
		echo $messenger->setRoute($language->get('home'), $registry->get('root'))->error($language->get('token_incorrect'), $language->get('error_occurred'));
		return;
	}

	/* install routing */

	if ($registry->get('file') === 'install.php' && $config->get('env') !== 'production')
	{
		if ($request->getPost('Redaxscript\View\InstallForm'))
		{
			$request->setSession('installArray',
			[
				'dbType' => $request->getPost('db-type'),
				'dbHost' => $request->getPost('db-host'),
				'dbName' => $request->getPost('db-name'),
				'dbUser' => $request->getPost('db-user'),
				'dbPassword' => $request->getPost('db-password'),
				'dbPrefix' => $request->getPost('db-prefix'),
				'adminName' => $request->getPost('admin-name'),
				'adminUser' => $request->getPost('admin-user'),
				'adminPassword' => $request->getPost('admin-password'),
				'adminEmail' => $request->getPost('admin-email')
			]);
			$installController = new Redaxscript\Controller\Install($registry, $request, $language, $config);
			echo $installController->process();
			return;
		}
		else
		{
			$installArray = $request->getSession('installArray');
			$systemStatus = new Redaxscript\View\SystemStatus($registry, $language);
			$installForm = new Redaxscript\View\InstallForm($registry, $language);
			echo $systemStatus->render() . $installForm->render($installArray);
			return;
		}
	}

	/* general routing */

	$post_list =
	[
		'Redaxscript\View\LoginForm' => 'Redaxscript\Controller\Login',
		'Redaxscript\View\RegisterForm' => 'Redaxscript\Controller\Register',
		'Redaxscript\View\ResetForm' => 'Redaxscript\Controller\Reset',
		'Redaxscript\View\RecoverForm' => 'Redaxscript\Controller\Recover',
		'Redaxscript\View\CommentForm' => 'Redaxscript\Controller\Comment'
	];
	foreach ($post_list as $key => $value)
	{
		if ($request->getPost($key))
		{
			if (class_exists($value))
			{
				$controller = new $value($registry, $request, $language);
				echo $controller->process();
			}
			return;
		}
	}

	/* search routing */

	if ($request->getPost('Redaxscript\View\SearchForm'))
	{
		$messenger = new Redaxscript\Messenger($registry);
		$aliasFilter = new Redaxscript\Filter\Alias();
		$table = $aliasFilter->sanitize($request->getPost('table'));
		$search = $aliasFilter->sanitize($request->getPost('search'));
		if ($table)
		{
			$table = '/' . $table;
		}
		echo $messenger->setRoute($language->get('continue'), 'search' . $table . '/' . $search)->doRedirect(0)->success($search);
	}

	/* parameter routing */

	switch ($firstParameter)
	{
		case 'admin':
			if ($registry->get('loggedIn') == $registry->get('token'))
			{
				admin_router();
			}
			else
			{
				echo $messenger->setRoute($language->get('login'), 'login')->error($language->get('access_no'), $language->get('error_occurred'));
			}
			return;
		case 'login':
			switch ($secondParameter)
			{
			case 'recover':
				if (Redaxscript\Db::getSetting('recovery') == 1)
				{
					$recoverForm = new Redaxscript\View\RecoverForm($registry, $language);
					echo $recoverForm->render();
					return;
				}
			case 'reset':
				if (Redaxscript\Db::getSetting('recovery') == 1 && $thirdParameter && $thirdSubParameter)
				{
					$resetForm = new Redaxscript\View\ResetForm($registry, $language);
					echo $resetForm->render();
					return;
				}

				/* show error */

				echo $messenger->setRoute($language->get('login'), 'login')->error($language->get('access_no'), $language->get('error_occurred'));
				return;
			default:
				$loginForm = new Redaxscript\View\LoginForm($registry, $language);
				echo $loginForm->render();
				return;
			}
		case 'logout':
			if ($registry->get('loggedIn') == $registry->get('token'))
			{
				$logoutController = new Redaxscript\Controller\Logout($registry, $request, $language);
				echo $logoutController->process();
				return;
			}

			/* show error */

			echo $messenger->setRoute($language->get('login'), 'login')->error($language->get('access_no'), $language->get('error_occurred'));
			return;
		case 'register':
			if (Redaxscript\Db::getSetting('registration'))
			{
				$registerForm = new Redaxscript\View\RegisterForm($registry, $language);
				echo $registerForm->render();
				return;
			}

			/* show error */

			echo $messenger->setRoute($language->get('home'), $registry->get('root'))->error($language->get('access_no'), $language->get('error_occurred'));
			return;
		case 'search':
			$searchController = new Redaxscript\Controller\Search($registry, $request, $language);
			echo $searchController->process();
			return;
		default:
			contents();
			return;
	}
	Redaxscript\Module\Hook::trigger('routerEnd');
}

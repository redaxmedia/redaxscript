<?php
namespace Redaxscript\Router;

use Redaxscript\Controller;
use Redaxscript\Filter;
use Redaxscript\Header;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;
use Redaxscript\View;

/**
 * parent class to provide the router
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Router
 * @author Henry Ruhs
 */

class Router extends RouterAbstract
{
	/**
	 * route the header
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	public function routeHeader() : bool
	{
		Module\Hook::trigger('routeHeader');

		/* handle break */

		if ($this->_registry->get('routerBreak'))
		{
			Header::responseCode(200);
		}

		/* handle guard */

		if ($this->_tokenGuard())
		{
			Header::responseCode(403);
		}
		if ($this->_authGuard())
		{
			Header::responseCode(403);
		}

		/* handle validator */

		if ($this->_aliasValidator())
		{
			Header::responseCode(200);
		}
		else if (!$this->_contentValidator())
		{
			Header::responseCode(404);
		}

		/* handle post */

		if ($this->_request->getPost('Redaxscript\View\SearchForm'))
		{
			return $this->_redirectSearch();
		}
		return (bool)$this->_registry->get('routerBreak');
	}

	/**
	 * route the content
	 *
	 * @since 3.3.0
	 *
	 * @return string|null
	 */

	public function routeContent() : ?string
	{
		Module\Hook::trigger('routeContent');
		$firstParameter = $this->getFirst();

		/* handle break */

		if ($this->_registry->get('routerBreak'))
		{
			return '<!-- routerBreak -->';
		}

		/* handle guard */

		if ($this->_tokenGuard())
		{
			return $this->_errorToken();
		}
		if ($this->_authGuard())
		{
			return $this->_errorAccess();
		}

		/* handle post */

		if ($this->_request->getPost('Redaxscript\View\CommentForm'))
		{
			return $this->_processComment();
		}
		if ($this->_request->getPost('Redaxscript\View\LoginForm'))
		{
			return $this->_processLogin();
		}
		if ($this->_request->getPost('Redaxscript\View\ResetForm'))
		{
			return $this->_processReset();
		}
		if ($this->_request->getPost('Redaxscript\View\RecoverForm'))
		{
			return $this->_processRecover();
		}
		if ($this->_request->getPost('Redaxscript\View\RegisterForm'))
		{
			return $this->_processRegister();
		}
		if (!$this->_installGuard() && $this->_request->getPost('Redaxscript\View\InstallForm'))
		{
			return $this->_processInstall();
		}

		/* handle route */

		if ($firstParameter === 'search')
		{
			return $this->_processSearch();
		}
		if ($firstParameter === 'login')
		{
			return $this->_renderLogin();
		}
		if ($firstParameter === 'logout')
		{
			return $this->_processLogout();
		}
		if ($firstParameter === 'register')
		{
			return $this->_renderRegister();
		}
		if (!$this->_installGuard())
		{
			return $this->_renderInstall();
		}
		return null;
	}

	/**
	 * token guard
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _tokenGuard() : bool
	{
		return $this->_request->get('post') && $this->_request->getPost('token') !== $this->_registry->get('token');
	}

	/**
	 * auth guard
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _authGuard() : bool
	{
		return $this->_registry->get('token') !== $this->_registry->get('loggedIn') && $this->_registry->get('firstParameter') === 'admin';
	}

	/**
	 * install guard
	 *
	 * @since 4.5.0
	 *
	 * @return bool
	 */

	protected function _installGuard() : bool
	{
		return $this->_registry->get('file') !== 'install.php' || $this->_config->get('lock');
	}

	/**
	 * alias validator
	 *
	 * @since 4.0.0
	 *
	 * @return bool
	 */

	protected function _aliasValidator() : bool
	{
		$aliasValidator = new Validator\Alias();
		return $aliasValidator->matchSystem($this->_registry->get('firstParameter'));
	}

	/**
	 * content validator
	 *
	 * @since 4.0.0
	 *
	 * @return bool
	 */

	protected function _contentValidator() : bool
	{
		return $this->_registry->get('lastId') > 0;
	}

	/**
	 * redirect the search
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _redirectSearch() : bool
	{
		$aliasFilter = new Filter\Alias();
		$root = $this->_registry->get('root');
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* handle post */

		$table = $aliasFilter->sanitize($this->_request->getPost('table'));
		$search = $aliasFilter->sanitize($this->_request->getPost('search'));
		$tableString = $table ? '/' . $table : null;
		$searchString = $search ? '/' . $search : null;

		/* redirect */

		return Header::doRedirect($root . '/' . $parameterRoute . 'search' . $tableString . $searchString);
	}

	/**
	 * process the search
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processSearch() : string
	{
		$searchController = new Controller\Search($this->_registry, $this->_request, $this->_language, $this->_config);
		return $searchController->process();
	}

	/**
	 * process the comment
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processComment() : string
	{
		$commentController = new Controller\Comment($this->_registry, $this->_request, $this->_language, $this->_config);
		return $commentController->process();
	}

	/**
	 * process the login
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processLogin() : string
	{
		$loginController = new Controller\Login($this->_registry, $this->_request, $this->_language, $this->_config);
		return $loginController->process();
	}

	/**
	 * process the reset
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processReset() : string
	{
		$resetController = new Controller\Reset($this->_registry, $this->_request, $this->_language, $this->_config);
		return $resetController->process();
	}

	/**
	 * process the recover
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processRecover() : string
	{
		$recoverController = new Controller\Recover($this->_registry, $this->_request, $this->_language, $this->_config);
		return $recoverController->process();
	}

	/**
	 * process the register
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processRegister() : string
	{
		$registerController = new Controller\Register($this->_registry, $this->_request, $this->_language, $this->_config);
		return $registerController->process();
	}

	/**
	 * process the logout
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processLogout() : string
	{
		$logoutController = new Controller\Logout($this->_registry, $this->_request, $this->_language, $this->_config);
		return $logoutController->process();
	}

	/**
	 * process the install
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _processInstall() : string
	{
		$emailFilter = new Filter\Email();
		$passwordFilter = new Filter\Password();
		$textFilter = new Filter\Text();
		$userFilter = new Filter\User();
		$this->_request->setSession('installArray',
		[
			'dbType' => $this->_request->getPost('db-type'),
			'dbHost' => $this->_request->getPost('db-host'),
			'dbName' => $this->_request->getPost('db-name'),
			'dbUser' => $this->_request->getPost('db-user'),
			'dbPassword' => $this->_request->getPost('db-password'),
			'dbPrefix' => $this->_request->getPost('db-prefix'),
			'adminName' => $textFilter->sanitize($this->_request->getPost('admin-name')),
			'adminUser' => $userFilter->sanitize($this->_request->getPost('admin-user')),
			'adminPassword' => $passwordFilter->sanitize($this->_request->getPost('admin-password')),
			'adminEmail' => $emailFilter->sanitize($this->_request->getPost('admin-email')),
		]);
		$installController = new Controller\Install($this->_registry, $this->_request, $this->_language, $this->_config);
		return $installController->process();
	}

	/**
	 * render the login
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _renderLogin() : string
	{
		$secondParameter = $this->getSecond();
		$thirdParameter = $this->getThird();
		$thirdSubParameter = $this->getThirdSub();
		$settingModel = new Model\Setting();

		/* handle login */

		if ($settingModel->get('recovery'))
		{
			if ($secondParameter === 'recover')
			{
				$recoverForm = new View\RecoverForm($this->_registry, $this->_language);
				return $recoverForm->render();
			}
			if ($secondParameter === 'reset' && $thirdParameter && $thirdSubParameter)
			{
				$resetForm = new View\ResetForm($this->_registry, $this->_language);
				return $resetForm->render();
			}
		}
		if (!$secondParameter)
		{
			$loginForm = new View\LoginForm($this->_registry, $this->_language);
			return $loginForm->render();
		}
		return $this->_errorAccess();
	}

	/**
	 * render the register
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _renderRegister() : string
	{
		$settingModel = new Model\Setting();
		if ($settingModel->get('registration'))
		{
			$registerForm = new View\RegisterForm($this->_registry, $this->_language);
			return $registerForm->render();
		}
		return $this->_errorAccess();
	}

	/**
	 * render the install
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _renderInstall() : string
	{
		$installArray = $this->_request->getSession('installArray');
		$systemStatus = new View\SystemStatus($this->_registry, $this->_language);
		$installForm = new View\InstallForm($this->_registry, $this->_language);
		return $systemStatus->render() . $installForm->render($installArray ? : []);
	}

	/**
	 * messenger factory
	 *
	 * @since 4.0.0
	 *
	 * @return View\Helper\Messenger
	 */

	protected function _messengerFactory() : View\Helper\Messenger
	{
		return new View\Helper\Messenger($this->_registry);
	}

	/**
	 * show the token error
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _errorToken() : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger
			->setUrl($this->_language->get('home'), $this->_registry->get('root'))
			->error($this->_language->get('token_incorrect'), $this->_language->get('error_occurred'));
	}

	/**
	 * show the access error
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _errorAccess() : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger
			->setUrl($this->_language->get('home'), $this->_registry->get('root'))
			->error($this->_language->get('access_no'), $this->_language->get('error_occurred'));
	}
}

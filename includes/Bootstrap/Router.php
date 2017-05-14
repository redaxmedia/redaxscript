<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Router as BaseRouter;

/**
 * children class to boot the router
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Router extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.1.0
	 */

	protected function _autorun()
	{
		$this->_setParameter();
		$this->_setRoute();
	}

	/**
	 * set parameter
	 *
	 * @since 3.1.0
	 */

	protected function _setParameter()
	{
		$parameter = new BaseRouter\Parameter($this->_request);
		$parameter->init();

		/* set registry */

		$this->_registry->set('firstParameter', $parameter->getFirst());
		$this->_registry->set('firstSubParameter', $parameter->getFirstSub());
		$this->_registry->set('secondParameter', $parameter->getSecond());
		$this->_registry->set('secondSubParameter', $parameter->getSecondSub());
		$this->_registry->set('thirdParameter', $parameter->getThird());
		$this->_registry->set('thirdSubParameter', $parameter->getThirdSub());
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token') && $this->_registry->get('firstParameter') === 'admin')
		{
			$this->_registry->set('adminParameter', $parameter->getAdmin());
			$this->_registry->set('tableParameter', $parameter->getTable());
			$this->_registry->set('idParameter', $parameter->getId());
			$this->_registry->set('aliasParameter', $parameter->getAlias());
		}
		$this->_registry->set('lastParameter', $parameter->getLast());
		$this->_registry->set('lastSubParameter', $parameter->getLastSub());
		$this->_registry->set('tokenParameter', $parameter->getToken());
	}

	/**
	 * set route
	 *
	 * @since 3.1.0
	 */

	protected function _setRoute()
	{
		$moduleArray = $this->_registry->get('moduleArray');
		$file = $this->_registry->get('file');
		$doRewrite = in_array('mod_rewrite', $moduleArray) && is_file('.htaccess') && $file === 'index.php';
		$resolver = new BaseRouter\Resolver($this->_request);
		$resolver->init();

		/* set registry */

		$this->_registry->set('liteRoute', $resolver->getLite());
		$this->_registry->set('fullRoute', $resolver->getFull());
		$this->_registry->set('parameterRoute', $doRewrite ? '' : '?p=');
		$this->_registry->set('languageRoute', $doRewrite ? '.' : '&amp;l=');
		$this->_registry->set('templateRoute', $doRewrite ? '.' : '&amp;t=');
	}
}

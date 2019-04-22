<?php
namespace Redaxscript\Template\Helper;

use Redaxscript\Db;
use Redaxscript\Model;

/**
 * helper class to provide a title helper
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Title extends HelperAbstract
{
	/**
	 * process
	 *
	 * @since 3.0.0
	 *
	 * @return string|null
	 */

	public function process() : ?string
	{
		$settingModel = new Model\Setting();
		$firstParameter = $this->_registry->get('firstParameter');
		$secondParameter = $this->_registry->get('secondParameter');
		$adminParameter = $this->_registry->get('adminParameter');
		$tableParameter = $this->_registry->get('tableParameter');
		$lastTable = $this->_registry->get('lastTable');
		$lastId = $this->_registry->get('lastId');
		$useTitle = $this->_registry->get('useTitle');
		$settingDivider = $settingModel->get('divider');
		$settingTitle = $settingModel->get('title');
		$title = null;

		/* find title */

		if ($useTitle)
		{
			$title = $useTitle;
		}

		/* handle admin */

		else if ($firstParameter === 'admin')
		{
			$title = $this->_language->get('administration');
			if ($adminParameter && $this->_language->get($adminParameter))
			{
				$title .= $settingDivider . $this->_language->get($adminParameter);
				if ($tableParameter && $this->_language->get($tableParameter))
				{
					$title .= $settingDivider . $this->_language->get($tableParameter);
				}
			}
		}

		/* handle login */

		else if ($firstParameter === 'login')
		{
			if ($secondParameter === 'recover')
			{
				$title = $this->_language->get('recovery');
			}
			else if ($secondParameter === 'reset')
			{
				$title = $this->_language->get('reset');
			}
			else
			{
				$title = $this->_language->get('login');
			}
		}

		/* handle logout */

		else if ($firstParameter === 'logout')
		{
			$title = $this->_language->get('logout');
		}

		/* handle register */

		else if ($firstParameter === 'register')
		{
			$title = $this->_language->get('registration');
		}

		/* handle module */

		else if ($firstParameter === 'module')
		{
			$title = $this->_language->get('module');
		}

		/* handle search */

		else if ($firstParameter === 'search')
		{
			$title = $this->_language->get('search');
		}

		/* handle error */

		else if (!$lastId)
		{
			$title = $this->_language->get('error');
		}
		else if ($lastTable && $lastId)
		{
			$title = Db::forTablePrefix($lastTable)->whereIdIs($lastId)->whereNull('access')->findOne()->title;
		}

		/* handle title */

		if ($title && $settingTitle)
		{
			return $title . $settingDivider . $settingTitle;
		}
		return $title ? : $settingTitle;
	}
}

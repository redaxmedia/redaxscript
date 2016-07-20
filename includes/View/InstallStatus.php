<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Messenger;

/**
 * children class to create the install status
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 *
 * @author Balázs Szilágyi
 */

class InstallStatus extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = null;

		$messageArray = $this->_validateError();
		if ($messageArray)
		{
			$output .= $this->_error(array(
				'message' => $messageArray
			));
		}

		$messageArray = $this->_validateWarning();
		if ($messageArray)
		{
			$output .= $this->_warning(array(
				'message' => $messageArray
			));
		}

		return $output;
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return array
	 */
	protected function _error($errorArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->error($errorArray['message']);
	}

	/**
	 * show the warning
	 *
	 * @since 3.0.0
	 *
	 * @param array $warningArray
	 *
	 * @return array
	 */
	protected function _warning($warningArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->warning($warningArray['message']);
	}

	/**
	 * validate error
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */
	protected function _validateError()
	{
		$messageArray = array();

		if (!$this->_registry->get('dbStatus'))
		{
			$messageArray[] = $this->_language->get('database_failed');
		}
		if (!$this->_registry->get('config'))
		{
			$messageArray[] = $this->_language->get('file_permission_grant') . $this->_language->get('colon') . ' config.php';
		}
		if (version_compare($this->_registry->get('phpVersion'), '5.3.10', '<'))
		{
			$messageArray[] = $this->_language->get('php_version_no', '_installation');
		}
		if (!$this->_registry->get('pdoDriver'))
		{
			$messageArray[] = $this->_language->get('pdo_no', '_installation');
		}
		if (!$this->_registry->get('sessionStatus'))
		{
			$messageArray[] = $this->_language->get('session_status_no', '_installation');
		}
		return $messageArray;
	}

	/**
	 * validate warning
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */
	protected function _validateWarning()
	{
		$messageArray = array();
		if ($this->_registry->get('osServer') != 'LINUX')
		{
			$messageArray[] = $this->_language->get('linux_no', '_installation');
		}
		if (!$this->_registry->get('htaccess'))
		{
			$messageArray[] = $this->_language->get('htaccess_no', '_installation');
		}
		if (!$this->_registry->get('modDeflate'))
		{
			$messageArray[] = $this->_language->get('mod_deflate_no', '_installation');
		}
		if (!$this->_registry->get('modRewrite'))
		{
			$messageArray[] = $this->_language->get('mod_rewrite_no', '_installation');
		}
		if (!$this->_registry->get('pdoMysql'))
		{
			$messageArray[] = $this->_language->get('pdo_mysql_no', '_installation');
		}
		if (!$this->_registry->get('pdoSqlite'))
		{
			$messageArray[] = $this->_language->get('pdo_sqlite_no', '_installation');
		}
		if (!$this->_registry->get('pdoPgsql'))
		{
			$messageArray[] = $this->_language->get('pdo_pgsql_no', '_installation');
		}
		return $messageArray;
	}
}

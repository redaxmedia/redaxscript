<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;
use Redaxscript\Messenger;

/**
 * children class to generate the install note
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 *
 * @author Balázs Szilágyi
 */

class InstallNote extends ViewAbstract
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
		$output = Hook::trigger('installNoteStart');

		if ($errorArray = $this->_validate())
		{
			$output .= $this->_errors($errorArray);
		}
		if ($warningArray = $this->_check())
		{
			$output .= $this->_warnings($warningArray);
		}

		$output .= Hook::trigger('installNoteEnd');
		return $output;
	}

	/**
	 * error messenger
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return array
	 */

	private function _errors($errorArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->error($errorArray);
	}

	/**
	 * warning messenger
	 *
	 * @since 3.0.0
	 *
	 * @param array $warningArray
	 *
	 * @return array
	 */

	private function _warnings($warningArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->warning($warningArray);
	}

	/**
	 * validate server requirements
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	private function _validate()
	{
		$errorArray = array();

		if (!$this->_registry->get('dbStatus'))
		{
			$errorArray[] = $this->_language->get('database_failed');
		}
		if (!is_writable('config.php'))
		{
			$errorArray[] = $this->_language->get('file_permission_grant') . $this->_language->get('colon') . ' config.php';
		}
		if (version_compare(phpversion(), '5.3.10', '<'))
		{
			$errorArray[] = 'PHP version is not high enough!';
		}
		if (!class_exists('PDO'))
		{
			$errorArray[] = 'PDO is not enabled!';
		}
		return $errorArray;
	}

	/**
	 * check for warnings
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	private function _check()
	{
		$moduleArray = function_exists('apache_get_modules') ? apache_get_modules() : array();
		$warningArray = array();

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$warningArray[] = 'Not running on linux!'; //$this->_language->get('server_not_linux')
		}
		if (!file_exists('.htaccess'))
		{
			$warningArray[] = 'There is no .htaccess file present!';
		}
		if ($moduleArray && !in_array('mod_rewrite', $moduleArray))
		{
			$warningArray[] = 'mod_rewrite is not enabled!';
		}
		if (!extension_loaded('pdo_mysql'))
		{
			$warningArray[] = 'No MySQL driver installed';
		}
		if (!extension_loaded('pdo_sqlite'))
		{
			$warningArray[] = 'No SQLite driver installed';
		}
		if (!extension_loaded('pdo_pgsql'))
		{
			$warningArray[] = 'No postgresql driver installed';
		}
		return $warningArray;
	}
}

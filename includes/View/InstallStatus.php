<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Hook;
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

/*TODO: the whole class does not work for unit testing, i would uncomment everything and start it from scratch via TDD */
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
		/* TODO: Wrong coding style ... follow the other controllers: $messageArray = $this->_validate(); */
		if ($errorArray = $this->_validate())
		{
			$output .= $this->_error($errorArray);
		}
		if ($warningArray = $this->_check())
		{
			$output .= $this->_warning($warningArray);
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
	/* TODO: Why private again? */
	private function _error($errorArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->error($errorArray);
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
	/* TODO: Why private again? */
	private function _warning($warningArray = array())
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->warning($warningArray);
	}

	/**
	 * validate error
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	/* TODO: why not protected? Rename it to validateError */
	private function _validate()
	{
		/* TODO: No language (en.json etc.) used, hard coded text is not allowed */
		/*TODO: Rename $errorArray to $messageArray */
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
	 * validate warning
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	/* TODO: why not protected? Rename it to validateWarning */
	private function _check()
	{
		$moduleArray = function_exists('apache_get_modules') ? apache_get_modules() : array();
		$warningArray = array();

		/* TODO: No language (en.json etc.) used, hard coded text is not allowed */
		/* TODO: Rename $errorArray to $messageArray */
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$warningArray[] = 'Not running on linux!';
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

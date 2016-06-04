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
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class InstallNote extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @param array $optionArray options of the installation
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function render($optionArray = array())
	{
		$output = Hook::trigger('installNoteStart');

		/*Not running under Linux
		No .htaccess file
		No mod_deflate
		No mod_rewrite
		*/

		$errorArray = $this->_errors();
		$warningArray = $this->_warnings();

		$messenger = new Messenger();

		$output .= $messenger->error($errorArray, $this->_language->get('alert'));
		$output .= $messenger->warning($warningArray, $this->_language->get('error_occurred'));

		$output .= Hook::trigger('installNoteEnd');
		return $output;
	}

	/**
	 * search for errors
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	private function _errors()
	{
		$errorArray = array();

		if (!is_writable('config.php'))
		{
			$errorArray[] =  $this->_language->get('file_permission_grant') . $this->_language->get('colon') . ' config.php';
		}
		if (version_compare(phpversion(), '5.3.10', '<'))
		{
			$errorArray[] =  "PHP version is not high enough!";
		}
		if (!in_array("mysql", PDO::getAvailableDrivers(), TRUE))
		{
			$errorArray[] =  "No MySQL driver installed";
		}
		if (!in_array("sqlite", PDO::getAvailableDrivers(), TRUE))
		{
			$errorArray[] =  "No MySQL driver installed";
		}
		if (!in_array("postresql", PDO::getAvailableDrivers(), TRUE))
		{
			$errorArray[] =  "No MySQL driver installed";
		}

		return $errorArray;
	}

	/**
	 * search for warnings
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	private function _warnings()
	{
		$warningArray = array();

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			array_push($warningArray, "Not running on linux!"); //$this->_language->get('server_not_linux')
		}
		if (!file_exists('.htaccess'))
		{
			array_push($warningArray, "There is no .htaccess file present!");
		}
		if (in_array('mod_rewrite', apache_get_modules()))
		{
			array_push($warningArray, "mod_rewrite is not enabled!");
		}

		return $warningArray;
	}
}

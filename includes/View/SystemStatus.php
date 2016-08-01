<?php
namespace Redaxscript\View;

use Redaxscript\Html;
use Redaxscript\Messenger;

/**
 * children class to create the system status
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Balázs Szilágyi
 */

class SystemStatus extends ViewAbstract
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

		/* handle error */

		$messageArray = $this->_validateError();
		if ($messageArray)
		{
			$output .= $this->_error(
			[
				'message' => $messageArray
			]);
		}

		/* handle warning */

		$messageArray = $this->_validateWarning();
		if ($messageArray)
		{
			$output .= $this->_warning(
			[
				'message' => $messageArray
			]);
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

	protected function _error($errorArray = [])
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

	protected function _warning($warningArray = [])
	{
		$messenger = new Messenger($this->_registry);
		return $messenger->warning($warningArray['message']);
	}

	/**
	 * validate the error
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _validateError()
	{
		$pdoDriverArray = $this->_registry->get('pdoDriverArray');
		$testDriverArray =
		[
			'sqlite',
			'mysql',
			'pgsql'
		];
		$messageArray = [];
		if (!$this->_registry->get('dbStatus'))
		{
			$messageArray[] = $this->_language->get('database_failed');
		}
		if (version_compare($this->_registry->get('phpVersion'), '5.4', '<'))
		{
			$messageArray[] = $this->_language->get('php_version_unsupported');
		}
		if (!array_intersect($pdoDriverArray, $testDriverArray))
		{
			$messageArray[] = $this->_language->get('pdo_driver_disabled');
		}
		if (!$this->_registry->get('sessionStatus'))
		{
			$messageArray[] = $this->_language->get('session_disabled');
		}
		return $messageArray;
	}

	/**
	 * validate the warning
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _validateWarning()
	{
		$apacheModuleArray = $this->_registry->get('apacheModuleArray');
		$testModuleArray =
		[
			'mod_deflate',
			'mod_headers',
			'mod_rewrite'
		];
		$messageArray = [];
		if ($this->_registry->get('phpOs') !== 'linux')
		{
			$messageArray[] = $this->_language->get('php_os_unsupported');
		}

		/* process module */

		foreach ($testModuleArray as $value)
		{
			if (!in_array($value, $apacheModuleArray))
			{
				$messageArray[] = $this->_language->get('apache_module_disabled') . $this->_language->get('colon') . ' ' . $value;
			}
		}
		return $messageArray;
	}
}

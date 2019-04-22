<?php
namespace Redaxscript\View;

use Redaxscript\Messenger;
use function in_array;
use function is_array;

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
	 * @return string|null
	 */

	public function render() : ?string
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
	 * validate the error
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _validateError() : array
	{
		$messageArray = [];
		if (!$this->_registry->get('dbStatus'))
		{
			$messageArray[] = $this->_language->get('database_failed');
		}
		if (!$this->_registry->get('phpStatus'))
		{
			$messageArray[] = $this->_language->get('php_version_unsupported');
		}
		if (!$this->_registry->get('driverArray'))
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

	protected function _validateWarning() : array
	{
		$messageArray = [];
		$phpOs = $this->_registry->get('phpOs');
		$moduleArray = $this->_registry->get('moduleArray');
		$testOsArray =
		[
			'linux',
			'windows'
		];
		$testModuleArray =
		[
			'mod_brotli',
			'mod_deflate',
			'mod_headers',
			'mod_rewrite'
		];
		if (!in_array($phpOs, $testOsArray))
		{
			$messageArray[] = $this->_language->get('php_os_unsupported');
		}

		/* process modules */

		foreach ($testModuleArray as $value)
		{
			if (!is_array($moduleArray) || !in_array($value, $moduleArray))
			{
				$messageArray[] = $this->_language->get('apache_module_disabled') . $this->_language->get('colon') . ' ' . $value;
			}
		}
		return $messageArray;
	}

	/**
	 * messenger factory
	 *
	 * @since 4.0.0
	 *
	 * @return Messenger
	 */

	protected function _messengerFactory() : Messenger
	{
		return new Messenger($this->_registry);
	}

	/**
	 * show the error
	 *
	 * @since 3.0.0
	 *
	 * @param array $errorArray
	 *
	 * @return string
	 */

	protected function _error(array $errorArray = []) : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger->error($errorArray['message']);
	}

	/**
	 * show the warning
	 *
	 * @since 3.0.0
	 *
	 * @param array $warningArray
	 *
	 * @return string
	 */

	protected function _warning(array $warningArray = []) : string
	{
		$messenger = $this->_messengerFactory();
		return $messenger->warning($warningArray['message']);
	}
}

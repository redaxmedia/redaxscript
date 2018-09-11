<?php
namespace Redaxscript\Client;

use Redaxscript\Request;

/**
 * abstract class to create a client class
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 *
 * @method protected _autorun()
 */

abstract class ClientAbstract
{
	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * output of the client
	 *
	 * @var string
	 */

	protected $_output;

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Request $request instance of the request class
	 */

	public function __construct(Request $request)
	{
		$this->_request = $request;
		$this->_autorun();
	}

	/**
	 * get the output
	 *
	 * @since 2.4.0
	 *
	 * @return string|null
	 */

	public function getOutput() : ?string
	{
		return $this->_output;
	}

	/**
	 * detect the required type
	 *
	 * @since 2.4.0
	 *
	 * @param array $setupArray array of client setup
	 * @param string $type type of the client
	 */

	protected function _detect(array $setupArray = [], string $type = null)
	{
		$userAgent = strtolower($this->_request->getServer('HTTP_USER_AGENT'));

		/* process setup */

		foreach ($setupArray as $value)
		{
			if (stristr($userAgent, $value))
			{
				/* general */

				$this->_output = $value;

				/* version */

				if ($type === 'version')
				{
					$this->_output = floor(substr($userAgent, strpos($userAgent, $value) + strlen($value) + 1, 3));
				}
			}
		}
	}
}

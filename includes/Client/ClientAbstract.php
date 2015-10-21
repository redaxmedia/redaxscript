<?php
namespace Redaxscript\Client;

use Redaxscript\Request;

/**
 * abstract class to build a client class
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Client
 * @author Henry Ruhs
 */

abstract class ClientAbstract
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * output of the client
	 *
	 * @var string|integer
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
	 * @return string
	 */

	public function getOutput()
	{
		return $this->_output;
	}

	/**
	 * detect the required type
	 *
	 * @since 2.4.0
	 *
	 * @param array $setup array of client setup
	 * @param string $type type of the client
	 */

	protected function _detect($setup = array(), $type = null)
	{
		$userAgent = strtolower($this->_request->getServer('HTTP_USER_AGENT'));

		/* process setup */

		foreach ($setup as $key => $value)
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

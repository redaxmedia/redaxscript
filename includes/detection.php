<?php

/**
 * The Detection class is a base class to detect required assets, it is extended for specific usages
 *
 * @since 2.0.0
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Detection
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * output
	 *
	 * @var string
	 */

	private $_output;

	/**
	 * constructor
	 *
	 * @since 2.0.0
	 */

	public function __construct(Redaxscript_Registry $registry)
	{
		$this->_registry = $registry;
		$this->init();
	}

	/**
	 * get the output of the detection
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */

	public function getOutput()
	{
		return $this->_output;
	}

	/**
	 * get a parameter from the $_GET superglobal array
	 *
	 * @since 2.0.0
	 *
	 * @param string $parameter The name of the parameter to get
	 * @return string
	 */

	protected function _getParameter($parameter = null)
	{
		if (isset($_GET[$parameter]))
		{
			/* clean parameter */

			$output = clean($_GET[$parameter], 1);
			return $output;
		}
	}

	/**
	 * detect the required language or template
	 *
	 * @since 2.0.0
	 *
	 * @param array $input Array of possible settings to define the required file
	 * @param string $type Type of file to detect
	 * @param string $route Path to the required file
	 */

	protected function _detect($input = null, $type = null, $route = null)
	{
		foreach ($input as $key => $value)
		{
			if ($value)
			{
				$file = str_replace('{type}', $value, $route);

				/* if file exists */

				if (file_exists($file))
				{
					$this->_output = $value;

					/* save parameter in session */

					if ($key === 'parameter')
					{
						$_SESSION[$this->_registry->get('root') . '/' . $type] = $value;
					}
					break;
				}
			}
		}
	}
}
?>
<?php

/**
 * Redaxscript Detection
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
	 * registry
	 *
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
	 * construct
	 *
	 * @since 2.0.0
	 */

	public function __construct(Redaxscript_Registry $registry)
	{
		$this->_registry = $registry;
		$this->init();
	}

	/**
	 * getOutput
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
	 * getParameter
	 *
	 * @since 2.0.0
	 *
	 * @param string $parameter
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
	 * detect
	 *
	 * @since 2.0.0
	 *
	 * @param array $input
	 * @param string $type
	 * @param string $route
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
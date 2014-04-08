<?php

/**
 * parent class to detect the required asset
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
	 * output of the detection
	 *
	 * @var string
	 */

	private $_output;

	/**
	 * constructor of the class
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
	 * get the superglobal parameter
	 *
	 * @since 2.0.0
	 *
	 * @param string $parameter name of the get parameter
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
	 * detect the required asset
	 *
	 * @since 2.0.0
	 *
	 * @param array $setup array of possible setup
	 * @param string $type type of the asset
	 * @param string $path path to the required file
	 */

	protected function _detect($setup = array(), $type = null, $path = null)
	{
		foreach ($setup as $key => $value)
		{
			if ($value)
			{
				$file = str_replace('{value}', $value, $path);

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

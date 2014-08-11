<?php
namespace Redaxscript;

/**
 * parent class to detect the required asset
 *
 * @since 2.0.0
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Detection
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
	 *
	 * @param Registry $registry instance of the registry class
	 */

	public function __construct(Registry $registry)
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
	 * detect the required asset
	 *
	 * @since 2.0.0
	 *
	 * @param array $setup array of detection setup
	 * @param string $type type of the asset
	 * @param string $path path to the required file
	 */

	protected function _detect($setup = array(), $type = null, $path = null)
	{
		foreach ($setup as $key => $value)
		{
			if (isset($value))
			{
				$file = str_replace('{value}', $value, $path);

				/* if file exists */

				if (file_exists($file))
				{
					$this->_output = $value;

					/* store query to session */

					if ($key === 'query')
					{
						$root = $this->_registry->get('root');
						Request::setSession($root . '/' . $type, $value);
					}
					break;
				}
			}
		}
	}
}

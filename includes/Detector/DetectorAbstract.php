<?php
namespace Redaxscript\Detector;

use Redaxscript\Registry;
use Redaxscript\Request;
use function is_file;
use function str_replace;

/**
 * abstract class to create a detector class
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Detector
 * @author Henry Ruhs
 */

abstract class DetectorAbstract implements DetectorInterface
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * output of the detector
	 *
	 * @var string
	 */

	protected $_output;

	/**
	 * placeholder for the file
	 *
	 * @var string
	 */

	protected $_filePlaceholder = '%FILE%';

	/**
	 * constructor of the class
	 *
	 * @since 2.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 */

	public function __construct(Registry $registry, Request $request)
	{
		$this->_registry = $registry;
		$this->_request = $request;
		$this->autorun();
	}

	/**
	 * get the output
	 *
	 * @since 2.0.0
	 *
	 * @return string|null
	 */

	public function getOutput() : ?string
	{
		return $this->_output;
	}

	/**
	 * detect the required asset
	 *
	 * @since 2.0.0
	 *
	 * @param string $type type of the asset
	 * @param string $path path to the required file
	 * @param array $setupArray array of detector setup
	 *
	 * @return string|null
	 */

	protected function _detect(string $type = null, string $path = null, array $setupArray = []) : ?string
	{
		foreach ($setupArray as $key => $value)
		{
			$file = str_replace($this->_filePlaceholder, $value, $path);
			if (is_file($file))
			{
				if ($key === 'query')
				{
					$this->_request->setSession($type, $value);
				}
				return $value;
			}
		}
		return null;
	}
}

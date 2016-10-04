<?php
namespace Redaxscript\Benchs;

use Redaxscript\Config;
use Redaxscript\Hash;

/**
 * HashBench
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Benchs
 * @author Henry Ruhs
 *
 * @BeforeMethods({"setUp"})
 */

class HashBench extends BenchCaseAbstract
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
	}

	/**
	 * providerHash
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerHash()
	{
		return $this->getProvider('tests/provider/hash.json');
	}

	/**
	 * benchInit
	 *
	 * @since 3.0.0
	 *
	 * @param array $parameterArray
	 *
	 * @ParamProviders({"providerHash"})
	 */

	public function benchInit($parameterArray = [])
	{
		$hash = new Hash($this->_config);
		$hash->init($parameterArray[0]);
	}

	/**
	 * benchValidate
	 *
	 * @since 3.0.0
	 *
	 * @param array $parameterArray
	 *
	 * @ParamProviders({"providerHash"})
	 */

	public function benchValidate($parameterArray = [])
	{
		/* setup */

		$hash = new Hash($this->_config);
		$hash->init($parameterArray[0]);

		/* bench */

		$hash->validate($parameterArray[0], function_exists('password_verify') ? $parameterArray[1][0][1] : $parameterArray[1][1]);
	}
}

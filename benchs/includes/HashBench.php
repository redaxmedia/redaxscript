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

class HashBench extends BenchCase
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
	 * @ParamProviders({"providerHash"})
	 */

	public function benchInit($parameter = array())
	{
		$hash = new Hash($this->_config);
		$hash->init($parameter[0]);
	}

	/**
	 * benchValidate
	 *
	 * @since 3.0.0
	 *
	 * @ParamProviders({"providerHash"})
	 */

	public function benchValidate($parameter = array())
	{
		/* setup */

		$hash = new Hash($this->_config);
		$hash->init($parameter[0]);

		/* bench */

		$hash->validate($parameter[0], function_exists('password_verify') ? $parameter[1][0][1] : $parameter[1][1]);
	}
}

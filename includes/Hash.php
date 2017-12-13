<?php
namespace Redaxscript;

/**
 * parent class to create a salted hash
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Hash
 * @author Henry Ruhs
 */

class Hash
{
	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected static $_config;

	/**
	 * plain raw
	 *
	 * @var string|int
	 */

	protected $_raw;

	/**
	 * salted hash
	 *
	 * @var string
	 */

	protected $_hash;

	/**
	 * constructor of the class
	 *
	 * @since 2.6.0
	 *
	 * @param Config $config instance of the config class
	 */

	public function __construct(Config $config)
	{
		$this->_config = $config;
	}

	/**
	 * init the class
	 *
	 * @since 2.6.0
	 *
	 * @param string|int $raw plain raw
	 */

	public function init($raw = null)
	{
		if (is_numeric($raw) || is_string($raw))
		{
			$this->_raw = $raw;
		}
		$this->_create();
	}

	/**
	 * get the raw
	 *
	 * @since 2.6.0
	 *
	 * @return string|int
	 */

	public function getRaw()
	{
		return $this->_raw;
	}

	/**
	 * get the hash
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function getHash() : string
	{
		return $this->_hash;
	}

	/**
	 * validate raw again hash
	 *
	 * @since 2.6.0
	 *
	 * @param string|int $raw plain raw
	 * @param string $hash salted hash
	 *
	 * @return bool
	 */

	public function validate($raw = null, string $hash = null) : bool
	{
		return function_exists('password_hash') ? password_verify($raw, $hash) : $hash === hash('sha512', $raw . $this->_config->get('salt'));
	}

	/**
	 * create a salted hash
	 *
	 * @since 2.6.0
	 */

	protected function _create()
	{
		$this->_hash = function_exists('password_verify') ? password_hash($this->_raw, PASSWORD_DEFAULT) : hash('sha512', $this->_raw . $this->_config->get('salt'));
	}
}

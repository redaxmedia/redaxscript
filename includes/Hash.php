<?php
namespace Redaxscript;

use function constant;
use function defined;
use function is_numeric;
use function is_string;
use function password_hash;
use function password_verify;

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
		return password_verify($raw, $hash);
	}

	/**
	 * create a salted hash
	 *
	 * @since 4.0.0
	 */

	protected function _create()
	{
		$this->_hash = password_hash($this->_raw, defined('PASSWORD_ARGON2I') ? constant('PASSWORD_ARGON2I') : PASSWORD_DEFAULT);
	}
}

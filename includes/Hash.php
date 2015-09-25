<?php
namespace Redaxscript;

/**
 * parent class to generate a salted hash
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
	 * @var object
	 */

	protected static $_config;
	
	/**
	 * salted hash
	 *
	 * @var string
	 */

	protected $_hash;

	/**
	 * raw data
	 *
	 * @var string
	 */

	protected $_raw;

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
	 * @param string $raw raw data
	 */

	public function init($raw = null)
	{
		if ($raw)
		{
			$this->_raw = $raw;
		}
		$this->_create();
	}

	/**
	 * get the hash
	 *
	 * @since 2.6.0
	 */

	public function getHash()
	{
		return $this->_hash;
	}

    /**
     * validate the hash
     *
     * @since 2.6.0
     *
     * @param string $raw raw data
     *
     * @return boolean
     */

    public function verifyHash($raw = null)
    {
        if (function_exists('password_verify'))
        {
            return password_verify($raw, $this->_hash);
        }
        return $this->_hash === hash('sha512', $raw . $this->_config->get('salt'));
    }

	/**
	 * create a salted hash
	 *
	 * @since 2.6.0
	 */

	protected function _create()
	{
        if (function_exists('password_hash'))
        {
            $this->_hash = password_hash($this->_raw, PASSWORD_BCRYPT);
        }
        else
        {
		    $this->_hash = hash('sha512', $this->_raw . $this->_config->get('salt'));
        }
	}
}

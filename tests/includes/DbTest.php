<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
use Redaxscript\Db;

/**
 * DbTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class DbTest extends TestCase
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
	 * @since 2.2.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
	}

	/**
	 * providerDb
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerDb()
	{
		return $this->getProvider('tests/provider/db.json');
	}

	/**
	 * testInit
	 *
	 * @since 2.2.0
	 *
	 * @param string $type
	 *
	 * @dataProvider providerDb
	 */

	public function testInit($type = null)
	{
		/* setup */

		$this->_config->set('type', $type);
		Db::init($this->_config);

		/* result */

		$result = Db::getDb();

		/* compare */

		$this->assertInstanceOf('PDO', $result);
	}
}
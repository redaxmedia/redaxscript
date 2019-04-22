<?php
namespace Redaxscript\Tests\Asset;

use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Asset;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;
use function file_get_contents;

/**
 * LoaderTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Asset\Loader
 */

class LoaderTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		Stream::setup('root', 0777, $this->getJSON('tests' . DIRECTORY_SEPARATOR . 'provider' . DIRECTORY_SEPARATOR . 'Asset' . DIRECTORY_SEPARATOR . 'LoaderTest_setUp.json'));
	}

	/**
	 * testConcat
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $collectionArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testConcat(array $registryArray = [], array $collectionArray = [], array $expectArray = []) : void
	{
		/* setup */

		$optionArray =
		[
			'directory' => Stream::url('root' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'styles'),
			'extension' => 'css',
			'attribute' => 'href',
			'lifetime' => 86400
		];
		$this->_registry->init($registryArray);
		$loader = new Asset\Loader($this->_registry);
		$loader
			->init($collectionArray)
			->concat($optionArray)
			->concat($optionArray);

		/* actual */

		$actualArray = $loader->getCollectionArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testRewrite
	 *
	 * @since 3.0.0
	 *
	 * @param array $collectionArray
	 * @param array $rewriteArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRewrite(array $collectionArray = [], array $rewriteArray = [], string $expect = null) : void
	{
		/* setup */

		$optionArray =
		[
			'directory' => Stream::url('root' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'styles'),
			'extension' => 'css',
			'attribute' => 'href',
			'lifetime' => 86400
		];
		$loader = new Asset\Loader(Registry::getInstance());
		$loader
			->init($collectionArray)
			->concat($optionArray, $rewriteArray)
			->concat($optionArray, $rewriteArray);

		/* actual */

		$file = $loader->getCollectionArray()['bundle']['href'];
		$actual = file_get_contents($file);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}

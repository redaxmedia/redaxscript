<?php
namespace Redaxscript\Tests\Asset;

use Redaxscript\Asset;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;

/**
 * LoaderTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class LoaderTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		Stream::setup('root', 0777, $this->getProvider('tests/provider/Asset/loader_setup.json'));
	}

	/**
	 * providerConcat
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerConcat() : array
	{
		return $this->getProvider('tests/provider/Asset/loader_concat.json');
	}

	/**
	 * providerRewrite
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRewrite() : array
	{
		return $this->getProvider('tests/provider/Asset/loader_rewrite.json');
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
	 * @dataProvider providerConcat
	 */

	public function testConcat(array $registryArray = [], array $collectionArray = [], array $expectArray = [])
	{
		/* setup */

		$optionArray =
		[
			'directory' => Stream::url('root/cache/styles'),
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
	 * @dataProvider providerRewrite
	 */

	public function testRewrite(array $collectionArray = [], array $rewriteArray = [], string $expect = null)
	{
		/* setup */

		$optionArray =
		[
			'directory' => Stream::url('root/cache/styles'),
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

<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * InstallNoteTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class InstallNoteTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;
	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/View/install_note_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($optionArray = array(), $expect = null)
	{
		/* setup */

		$installNoteForm = new View\InstallNote($this->_registry, $this->_language);

		/* actual */

		$actual = $installNoteForm->render();

		chmod('./config.php', $optionArray['configPermission']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}

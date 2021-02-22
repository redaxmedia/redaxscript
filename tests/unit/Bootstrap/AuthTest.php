<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Auth;
use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * AuthTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\Auth
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 *
 * @runTestsInSeparateProcesses
 */

class AuthTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_request->init();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertUsers($optionArray);
		$installer->insertGroups();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testAuth
	 *
	 * @since 3.1.0
	 *
	 * @param int $userId
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testAuth(int $userId = null, array $expectArray = []) : void
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->login($userId);
		new Bootstrap\Auth($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'myId' => $this->_registry->get('myId'),
			'myName' => $this->_registry->get('myName'),
			'myUser' => $this->_registry->get('myUser'),
			'myEmail' => $this->_registry->get('myEmail'),
			'myLanguage' => $this->_registry->get('myLanguage'),
			'myGroups' => $this->_registry->get('myGroups'),
			'categoriesNew' => $this->_registry->get('categoriesNew'),
			'categoriesEdit' => $this->_registry->get('categoriesEdit'),
			'categoriesDelete' => $this->_registry->get('categoriesDelete'),
			'articlesNew' => $this->_registry->get('articlesNew'),
			'articlesEdit' => $this->_registry->get('articlesEdit'),
			'articlesDelete' => $this->_registry->get('articlesDelete'),
			'extrasNew' => $this->_registry->get('extrasNew'),
			'extrasEdit' => $this->_registry->get('extrasEdit'),
			'extrasDelete' => $this->_registry->get('extrasDelete'),
			'commentsNew' => $this->_registry->get('commentsNew'),
			'commentsEdit' => $this->_registry->get('commentsEdit'),
			'commentsDelete' => $this->_registry->get('commentsDelete'),
			'groupsNew' => $this->_registry->get('groupsNew'),
			'groupsEdit' => $this->_registry->get('groupsEdit'),
			'groupsDelete' => $this->_registry->get('groupsDelete'),
			'usersNew' => $this->_registry->get('usersNew'),
			'usersEdit' => $this->_registry->get('usersEdit'),
			'usersDelete' => $this->_registry->get('usersDelete'),
			'modulesInstall' => $this->_registry->get('modulesInstall'),
			'modulesEdit' => $this->_registry->get('modulesEdit'),
			'modulesUninstall' => $this->_registry->get('modulesUninstall'),
			'settingsEdit' => $this->_registry->get('settingsEdit'),
			'filter' => $this->_registry->get('filter'),
			'tableNew' => $this->_registry->get('tableNew'),
			'tableInstall' => $this->_registry->get('tableInstall'),
			'tableEdit' => $this->_registry->get('tableEdit'),
			'tableDelete' => $this->_registry->get('tableDelete'),
			'tableUninstall' => $this->_registry->get('tableUninstall')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}

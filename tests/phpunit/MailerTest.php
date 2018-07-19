<?php
namespace Redaxscript\Tests;

use Redaxscript\Mailer;
use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;

/**
 * MailerTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Mailer
 */

class MailerTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		Stream::setup('root');
		$file = new StreamFile('attachment.zip');
		StreamWrapper::getRoot()->addChild($file);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * testSend
	 *
	 * @since 2.2.0
	 *
	 * @param array $toArray
	 * @param array $fromArray
	 * @param string $subject
	 * @param string|array $body
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSend(array $toArray = [], array $fromArray = [], string $subject = null, $body = null)
	{
		/* setup */

		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $body);

		/* actual */

		$actual = $mailer->send();

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testSendAttachment
	 *
	 * @since 3.1.0
	 *
	 * @param array $toArray
	 * @param array $fromArray
	 * @param string $subject
	 * @param string|array $body
	 *
	 * @requires OS Linux
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSendAttachment(array $toArray = [], array $fromArray = [], string $subject = null, $body = null)
	{
		/* setup */

		$attachmentArray =
		[
			Stream::url('root' . DIRECTORY_SEPARATOR . 'attachment.zip')
		];
		$mailer = new Mailer();
		$mailer->init($toArray, $fromArray, $subject, $body, $attachmentArray);

		/* actual */

		$actual = $mailer->send();

		/* compare */

		$this->assertTrue($actual);
	}
}

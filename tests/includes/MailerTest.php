<?php
namespace Redaxscript\Tests;
use Redaxscript\Mailer;

/**
 * MailerTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class MailerTest extends TestCase
{
	/**
	 * providerMailer
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerMailer()
	{
		return $this->getProvider('tests/provider/mailer.json');
	}

	/**
	 * testMessage
	 *
	 * @since 2.2.0
	 *
	 * @param array $toArray
	 * @param array $fromArray
	 * @param string $subject
	 * @param mixed $body
	 * @param array $attachmentArray
	 *
	 * @dataProvider providerMailer
	 */

	public function testMessage($toArray = array(), $fromArray = array(), $subject = null, $body = null, $attachmentArray = array())
	{
		/* setup */

		$mailer = new Mailer($toArray, $fromArray, $subject, $body, $attachmentArray);

		/* result */

		$mailer->send();

		/*compare */

		$this->assertTrue(true);
	}
}

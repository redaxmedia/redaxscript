<?php

/**
 * Redaxscript Mailer Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Test
 * @author Henry Ruhs
 */

class Redaxscript\Mailer_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/mailer.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testMessage
	 *
	 * @since 2.2.0
	 *
	 * @param array $toArray
	 * @param array $fromArray
	 * @param string $subject
	 * @param string|array $body
	 * @param array $attachmentArray
	 *
	 * @dataProvider providerMailer
	 */

	public function testMessage($toArray = array(), $fromArray = array(), $subject = null, $body = null, $attachmentArray = array())
	{
		/* setup */

		$mailer = new Redaxscript\Mailer($toArray, $fromArray, $subject, $body, $attachmentArray);

		/* result */

		$mailer->send();

		/*compare */

		$this->assertTrue(true);
	}
}
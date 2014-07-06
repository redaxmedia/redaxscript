<?php

/**
 * Redaxscript Mailer Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Mailer_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * instance of the guzzel class
	 *
	 * @var object
	 */

	protected $_guzzel;

	/**
	 * array of recipient
	 *
	 * @var array
	 */

	protected $_toArray;

	/**
	 * array of sender
	 *
	 * @var array
	 */

	protected $_fromArray;

	/**
	 * subject of the email
	 *
	 * @var string
	 */

	protected $_subject;

	/**
	 * array of body items
	 *
	 * @var array
	 */

	protected $_bodyArray;

	/**
	 * array of attachments
	 *
	 * @var array
	 */

	protected $_attachmentArray;

	/**
	 * setUp
	 *
	 * @since 2.2.0
	 */

	protected function setUp()
	{
		/* client */

		$this->_guzzel = new \Guzzle\Http\Client('http://127.0.0.1:1080');
		$this->mailcatcher->delete('/messages')->send();

		/* data */

		$this->_toArray = array(
			'To' => 'to@redaxscript.com'
		);
		$this->_fromArray = array(
			'From' => 'from@redaxscript.com'
		);
		$this->_subject = 'Test';
		$this->_bodyArray = array(
			'Test' => 'test'
		);
	}

	/**
	 * testMessage
	 *
	 * @since 2.2.0
	 */

	public function testMessage()
	{
		$mailer = new Redaxscript_Mailer($this->_toArray, $this->_fromArray, $this->_subject, $this->_bodyArray);
		$mailer->send();
	}
}
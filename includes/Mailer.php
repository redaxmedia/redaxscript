<?php
namespace Redaxscript;

/**
 * parent class to send an mail
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Mailer
 * @author Henry Ruhs
 */

class Mailer
{
	/**
	 * array of recipient
	 *
	 * @var array
	 */

	protected $_toArray = [];

	/**
	 * array of sender
	 *
	 * @var array
	 */

	protected $_fromArray = [];

	/**
	 * subject of the email
	 *
	 * @var string
	 */

	protected $_subject;

	/**
	 * body of the email
	 *
	 * @var mixed
	 */

	protected $_body;

	/**
	 * array of attachments
	 *
	 * @var array
	 */

	protected $_attachmentArray = [];

	/**
	 * built recipient contents
	 *
	 * @var string
	 */

	protected $_fromString;

	/**
	 * built subject contents
	 *
	 * @var string
	 */

	protected $_subjectString;

	/**
	 * built body contents
	 *
	 * @var string
	 */

	protected $_bodyString;

	/**
	 * built header contents
	 *
	 * @var string
	 */

	protected $_headerString;

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param array $toArray array of recipient
	 * @param array $fromArray array of sender
	 * @param string $subject subject of the email
	 * @param mixed $body body of the email
	 * @param array $attachmentArray array of attachments
	 */

	public function init($toArray = [], $fromArray = [], $subject = null, $body = null, $attachmentArray = [])
	{
		$this->_toArray = $toArray;
		$this->_fromArray = $fromArray;
		$this->_subject = $subject;
		$this->_body = $body;
		$this->_attachmentArray = $attachmentArray;

		/* create as needed */

		$this->_createFromString();
		$this->_createSubjectString();
		$this->_createBodyString();
		$this->_createHeaderString();
	}

	/**
	 * create the recipient contents
	 *
	 * @since 2.0.0
	 */

	protected function _createFromString()
	{
		/* create from string */

		$from = current($this->_fromArray);
		$fromName = key($this->_fromArray);

		/* from name fallback */

		if (!$fromName)
		{
			$fromName = $from;
		}
		$this->_fromString = $fromName . ' <' . $from . '>';
	}

	/**
	 * create the subject contents
	 *
	 * @since 2.0.0
	 */

	protected function _createSubjectString()
	{
		/* collect subject string */

		$subject = Db::getSetting('subject');

		/* extended subject string */

		if ($subject)
		{
			$this->_subjectString = $subject;
			if ($this->_subject)
			{
				$this->_subjectString .= Db::getSetting('divider');
			}
		}
		$this->_subjectString .= $this->_subject;
	}

	/**
	 * create the body contents
	 *
	 * @since 2.0.0
	 */

	protected function _createBodyString()
	{
		if (is_array($this->_body))
		{
			foreach ($this->_body as $key => $value)
			{

				$this->_bodyString .= $value;
			}
		}
		else
		{
			$this->_bodyString = $this->_body;
		}
	}

	/**
	 * create the header contents
	 *
	 * @since 2.0.0
	 */

	protected function _createHeaderString()
	{
		/* collect header string */

		$this->_headerString = 'MIME-Version: 1.0' . PHP_EOL;

		/* empty attachment */

		if (!$this->_attachmentArray)
		{
			$this->_headerString .= 'Content-Type: text/html; charset=' . Db::getSetting('charset') . PHP_EOL;
		}

		/* else handle attachment */

		else
		{
			foreach ($this->_attachmentArray as $file => $contents)
			{
				$contents = chunk_split(base64_encode($contents));
				$boundary = uniqid();
				$this->_headerString .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= '--' . uniqid() . PHP_EOL;

				/* integrate body string */

				if ($this->_bodyString)
				{
					$this->_headerString .= 'Content-Type: text/html; charset=' . Db::getSetting('charset') . PHP_EOL;
					$this->_headerString .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
					$this->_headerString .= $this->_bodyString . PHP_EOL . PHP_EOL;
					$this->_headerString .= '--' . $boundary . PHP_EOL;

					/* reset body string */

					$this->_bodyString = null;
				}
				$this->_headerString .= 'Content-Type: application/octet-stream; name="' . $file . '"' . PHP_EOL;
				$this->_headerString .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
				$this->_headerString .= 'Content-Disposition: attachment; filename="' . $file . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= $contents . PHP_EOL . PHP_EOL;
				$this->_headerString .= '--' . $boundary . '--';
			}
		}

		/* collect from output */

		$this->_headerString .= 'From: ' . $this->_fromString . PHP_EOL;
		$this->_headerString .= 'Reply-To: ' . $this->_fromString . PHP_EOL;
		$this->_headerString .= 'Return-Path: ' . $this->_fromString . PHP_EOL;
	}

	/**
	 * send the email
	 *
	 * @since 2.6.2
	 *
	 * @return boolean
	 */

	public function send()
	{
		$output = false;
		if (function_exists('mail'))
		{
			foreach ($this->_toArray as $to)
			{
				if ($to)
				{
					$output = mail($to, $this->_subjectString, $this->_bodyString, $this->_headerString);
				}
			}
		}
		return $output;
	}
}

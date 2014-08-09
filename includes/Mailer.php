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

	protected $_attachmentArray;

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
	 * constructor of the class
	 *
	 * @since 2.2.0
	 *
	 * @param array $toArray array of recipient
	 * @param array $fromArray array of sender
	 * @param string $subject subject of the email
	 * @param mixed $body body of the email
	 * @param array $attachmentArray array of attachments
	 */

	public function __construct($toArray = array(), $fromArray = array(), $subject = null, $body = null, $attachmentArray = array())
	{
		$this->_toArray = $toArray;
		$this->_fromArray = $fromArray;
		$this->_subject = $subject;
		$this->_body = $body;
		$this->_attachmentArray = $attachmentArray;
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		if (function_exists('mail'))
		{
			$this->_buildFromString();
			$this->_buildSubjectString();
			$this->_buildBodyString();
			$this->_buildHeaderString();
		}
	}

	/**
	 * build the recipient contents
	 *
	 * @since 2.0.0
	 */

	protected function _buildFromString()
	{
		/* build from string */

		$from = current($this->_fromArray);
		$fromName = key($this->_fromArray);

		/* fallback if empty */

		if (!$fromName)
		{
			$fromName = $from;
		}
		$this->_fromString = $fromName . ' <' . $from . '>';
	}

	/**
	 * build the subject contents
	 *
	 * @since 2.0.0
	 */

	protected function _buildSubjectString()
	{
		/* collect subject string */

		$settingsSubject = Db::getSettings('subject');

		/* extended subject string */

		if ($settingsSubject)
		{
			$this->_subjectString = $settingsSubject;
			if ($this->_subject)
			{
				$this->_subjectString .= Db::getSettings('divider');
			}
		}
		$this->_subjectString .= $this->_subject;
	}

	/**
	 * build the body contents
	 *
	 * @since 2.0.0
	 */

	protected function _buildBodyString()
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
	 * build the header contents
	 *
	 * @since 2.0.0
	 */

	protected function _buildHeaderString()
	{
		/* collect header string */

		$this->_headerString = 'MIME-Version: 1.0' . PHP_EOL;

		/* if empty attachment */

		if (empty($this->_attachmentArray))
		{
			$this->_headerString .= 'Content-Type: text/html; charset=' . Db::getSettings('charset') . PHP_EOL;
		}

		/* else handle attachment */

		else
		{
			foreach ($this->_attachmentArray as $fileName => $fileContents)
			{
				$fileContents = chunk_split(base64_encode($fileContents));
				$boundary = uniqid();
				$this->_headerString .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= '--' . uniqid() . PHP_EOL;

				/* integrate body string */

				if ($this->_bodyString)
				{
					$this->_headerString .= 'Content-Type: text/html; charset=' . Db::getSettings('charset') . PHP_EOL;
					$this->_headerString .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
					$this->_headerString .= $this->_bodyString . PHP_EOL . PHP_EOL;
					$this->_headerString .= '--' . $boundary . PHP_EOL;

					/* reset body string */

					$this->_bodyString = '';
				}
				$this->_headerString .= 'Content-Type: application/octet-stream; name="' . $fileName . '"' . PHP_EOL;
				$this->_headerString .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
				$this->_headerString .= 'Content-Disposition: attachment; filename="' . $fileName . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= $fileContents . PHP_EOL . PHP_EOL;
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
	 * @since 2.0.0
	 */

	public function send()
	{
		foreach ($this->_toArray as $toName => $to)
		{
			/* fallback if empty */

			if (!$toName)
			{
				$toName = $to;
			}
			$toString = $toName . ' <' . $to . '>';

			/* send mail */

			mail($toString, $this->_subjectString, $this->_bodyString, $this->_headerString);
		}
	}
}

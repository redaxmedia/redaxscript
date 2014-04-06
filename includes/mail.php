<?php

/**
 * The Mail class provides methods to format and send an email
 *
 * This class requires the PHP send() function
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Mail
 * @author Henry Ruhs
 */

class Redaxscript_Mail
{
	/**
	 * list of recipients
	 *
	 * associative array in the form name => address
	 *
	 * @var array
	 */

	private $_toArray;

	/**
	 * sender's name and email address
	 *
	 * associative array in the from name => address
	 *
	 * @var array
	 */

	private $_fromArray;

	/**
	 * subject of the email
	 *
	 * @var string
	 */

	private $_subject;

	/**
	 * items to form the body of the email
	 *
	 * key/value pairs display as item: description each on a separate line
	 * elements with numeric keys are simply concatenated
	 *
	 * @var array
	 */

	private $_bodyArray;
	/**
	 * list of attachments
	 *
	 * associative array in the form filename => contents
	 *
	 * @var array
	 */

	private $_attachmentArray;

	/**
	 * formatted string used for From, Reply-To and Return-Path fields
	 *
	 * @var string
	 */

	protected $_fromString;

	/**
	 * formatted string used for Subject field
	 *
	 * @var string
	 */

	protected $_subjectString;

	/**
	 * formatted body of the email
	 *
	 * @var string
	 */

	protected $_bodyString;

	/**
	 * formatted email header
	 *
	 * @var string
	 */

	protected $_headerString;

	/**
	 * constructor
	 *
	 * @since 2.0.0
	 *
	 * @param array $toArray List of recipients
	 * @param array $fromArray Sender's name and email address
	 * @param string $subject Subject of the email
	 * @param array $bodyArray Items to include in the email body
	 * @param array $attachmentArray Files to send as attachments
	 */

	public function __construct($toArray = array(), $fromArray = array(), $subject = null, $bodyArray = array(), $attachmentArray = array())
	{
		$this->_toArray = $toArray;
		$this->_fromArray = $fromArray;
		$this->_subject = $subject;
		$this->_bodyArray = $bodyArray;
		$this->_attachmentArray = $attachmentArray;

		/* call init */

		$this->init();
	}

	/**
	 * build the formatted strings to form the email
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
	 * build the contents of the From field
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
	 * build the contents of the Subject field
	 *
	 * @since 2.0.0
	 */

	protected function _buildSubjectString()
	{
		/* collect subject string */

		$settings_subject = s('subject');

		/* extended subject string */

		if ($settings_subject)
		{
			$this->_subjectString = $settings_subject;
			if ($this->_subject)
			{
				$this->_subjectString .= s('divider');
			}
		}
		$this->_subjectString .= $this->_subject;
	}

	/**
	 * build the body of the message
	 *
	 * @since 2.0.0
	 */

	protected function _buildBodyString()
	{
		/* collect body string */

		foreach ($this->_bodyArray as $key => $value)
		{
			if ($key && $value)
			{
				/* if numeric key */

				if (is_numeric($key))
				{
					$this->_bodyString .= $value;
				}

				/* else format parts */

				else
				{
					$this->_bodyString .= '<strong>' . $key . ':</strong> ' . $value . '<br />';
				}
			}
		}
	}

	/**
	 * build the message header
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
			$this->_headerString .= 'Content-Type: text/html; charset=' . s('charset') . PHP_EOL;
		}

		/* else handle attachment */

		else
		{
			foreach ($this->_attachmentArray as $fileName => $fileContents)
			{
				$fileContents = chunk_split(base64_encode($fileContents));
				$this->_headerString .= 'Content-Type: multipart/mixed; boundary="' . TOKEN . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= '--' . TOKEN . PHP_EOL;

				/* integrate body string */

				if ($this->_bodyString)
				{
					$this->_headerString .= 'Content-Type: text/html; charset=' . s('charset') . PHP_EOL;
					$this->_headerString .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
					$this->_headerString .= $this->_bodyString . PHP_EOL . PHP_EOL;
					$this->_headerString .= '--' . TOKEN . PHP_EOL;

					/* reset body string */

					$this->_bodyString = '';
				}
				$this->_headerString .= 'Content-Type: application/octet-stream; name="' . $fileName . '"' . PHP_EOL;
				$this->_headerString .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
				$this->_headerString .= 'Content-Disposition: attachment; filename="' . $fileName . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= $fileContents . PHP_EOL . PHP_EOL;
				$this->_headerString .= '--' . TOKEN . '--';
			}
		}

		/* collect from output */

		$this->_headerString .= 'From: ' . $this->_fromString . PHP_EOL;
		$this->_headerString .= 'Reply-To: ' . $this->_fromString . PHP_EOL;
		$this->_headerString .= 'Return-Path: ' . $this->_fromString . PHP_EOL;
	}

	/**
	 * send the email message using PHP's send() function
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
?>
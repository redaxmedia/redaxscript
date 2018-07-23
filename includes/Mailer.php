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
	 * array of the recipient
	 *
	 * @var array
	 */

	protected $_toArray = [];

	/**
	 * array of the sender
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
	 * @var string|array
	 */

	protected $_body;

	/**
	 * array of the attachments
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
	 * @param array $toArray array of the recipient
	 * @param array $fromArray array of the sender
	 * @param string $subject subject of the email
	 * @param string|array $body body of the email
	 * @param array $attachmentArray array of the attachments
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
		$settingModel = new Model\Setting();

		/* collect subject string */

		$subject = $settingModel->get('subject');

		/* extended subject string */

		if ($subject)
		{
			$this->_subjectString = $subject;
			if ($this->_subject)
			{
				$this->_subjectString .= $settingModel->get('divider');
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
		$this->_bodyString = is_array($this->_body) ? implode(PHP_EOL, $this->_body) : $this->_body;
	}

	/**
	 * create the header contents
	 *
	 * @since 2.0.0
	 */

	protected function _createHeaderString()
	{
		$settingModel = new Model\Setting();

		/* collect header string */

		$this->_headerString = 'MIME-Version: 1.0' . PHP_EOL;

		/* handle attachment */

		if ($this->_attachmentArray)
		{
			foreach ($this->_attachmentArray as $attachment)
			{
				if (is_file($attachment))
				{
					$content = trim(chunk_split(base64_encode($attachment)));
					$boundary = uniqid();
					$this->_headerString .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . PHP_EOL;
					$this->_headerString .= '--' . $boundary . PHP_EOL;

					/* handle body string */

					if ($this->_bodyString)
					{
						$this->_headerString .= 'Content-Type: text/html; charset=' . $settingModel->get('charset') . PHP_EOL;
						$this->_headerString .= 'Content-Transfer-Encoding: 8bit' . PHP_EOL;
						$this->_headerString .= $this->_bodyString . PHP_EOL;
						$this->_headerString .= '--' . $boundary . PHP_EOL;

						/* reset body string */

						$this->_bodyString = null;
					}
					$this->_headerString .= 'Content-Type: application/octet-stream; name="' . $attachment . '"' . PHP_EOL;
					$this->_headerString .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
					$this->_headerString .= 'Content-Disposition: attachment; filename="' . $attachment . '"' . PHP_EOL;
					$this->_headerString .= $content . PHP_EOL;
					$this->_headerString .= '--' . $boundary . '--';
				}
			}
		}
		else
		{
			$this->_headerString .= 'Content-Type: text/html; charset=' . $settingModel->get('charset') . PHP_EOL;
		}

		/* collect header string */

		$this->_headerString .= 'From: ' . $this->_fromString . PHP_EOL;
		$this->_headerString .= 'Reply-To: ' . $this->_fromString . PHP_EOL;
		$this->_headerString .= 'Return-Path: ' . $this->_fromString . PHP_EOL;
	}

	/**
	 * send the email
	 *
	 * @since 2.6.2
	 *
	 * @return bool
	 */

	public function send() : bool
	{
		foreach ($this->_toArray as $to)
		{
			if (!function_exists('mail') || !mail($to, $this->_subjectString, $this->_bodyString, $this->_headerString))
			{
				return false;
			};
		}
		return true;
	}
}

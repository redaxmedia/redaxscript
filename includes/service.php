<?php

/**
 * Redaxscript Mail
 *
 * @since 1.3
 *
 * @category Service
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Mail
{
	/**
	 * toArray
	 * @var array
	 */

	private $_toArray;

	/**
	 * fromArray
	 * @var array
	 */

	private $_fromArray;

	/**
	 * subjectArray
	 * @var object
	 */

	private $_subject;

	/**
	 * bodyArray
	 * @var array
	 */

	private $_bodyArray;
	/**
	 * attachmentArray
	 * @var array
	 */

	private $_attachmentArray;

	/**
	 * formString
	 * @var string
	 */

	protected $_formString;

	/**
	 * subjectString
	 * @var string
	 */

	protected $_subjectString;

	/**
	 * bodyString
	 * @var string
	 */

	protected $_bodyString;

	/**
	 * headerString
	 * @var string
	 */

	protected $_headerString;

	/**
	 * construct
	 *
	 * @since 1.3
	 * 
	 * @param $toArray array
	 * @param $fromArray array
	 * @param $subject string
	 * @param $bodyArray array
	 * @param $attachmentArray array
	 */

	public function __construct($toArray = '', $fromArray = '', $subject = '', $bodyArray = '', $attachmentArray = '')
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
	 * init
	 *
	 * @since 1.3
	 */

	public function init()
	{
		if (function_exists('mail'))
		{
			$this->_buildFromString();
			$this->_buildSubjectString();
			$this->_buildBodyString();
			$this->_buildHeaderString();
			$this->_send();
		}
	}

	/**
	 * buildFromString
	 *
	 * @since 1.3
	 */

	protected function _buildFromString()
	{
		/* build from string */

		$from = $this->_from[0];
		$fromName = $this->_from[1];

		/* fallback if numeric */

		if (is_numeric($from))
		{
			$from = $fromName;
		}
		$this->_fromString = $from . ' <' . $fromName . '>';
	}

	/**
	 * buildSubjectString
	 *
	 * @since 1.3
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
	 * buildBodyString
	 *
	 * @since 1.3
	 */

	protected function _buildBodyString()
	{
		/* collect body string */

		foreach ($this->_bodyArray as $key => $value)
		{
			if ($key && $value)
			{
				/* if numeric */

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
	 * buildHeaderString
	 *
	 * @since 1.3
	 */

	protected function _buildHeaderString()
	{
		/* collect header string */

		$this->_headerString = 'mime-version: 1.0' . PHP_EOL;

		/* if email attachment */

		if (is_array($this->_attachmentArray))
		{
			foreach ($this->_attachmentArray as $fileName => $fileContents)
			{
				$fileContents = chunk_split(base64_encode($fileContents));
				$this->_headerString .= 'content-type: multipart/mixed; boundary="' . TOKEN . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= '--' . TOKEN . PHP_EOL;

				/* integrate body string */

				if ($this->_bodyString)
				{
					$this->_headerString .= 'content-type: text/html; charset=' . s('charset') . PHP_EOL;
					$this->_headerString .= 'content-transfer-encoding: 8bit' . PHP_EOL . PHP_EOL;
					$this->_headerString .= $this->_bodyString . PHP_EOL . PHP_EOL;
					$this->_headerString .= '--' . TOKEN . PHP_EOL;
					$this->_bodyString = '';
				}
				$this->_headerString .= 'content-type: application/octet-stream; name="' . $fileName . '"' . PHP_EOL;
				$this->_headerString .= 'content-transfer-encoding: base64' . PHP_EOL;
				$this->_headerString .= 'content-disposition: attachment; filename="' . $fileName . '"' . PHP_EOL . PHP_EOL;
				$this->_headerString .= $fileContents . PHP_EOL . PHP_EOL;
				$this->_headerString .= '--' . TOKEN . '--';
			}
		}
		else
		{
			$this->_headerString .= 'content-type: text/html; charset=' . s('charset') . PHP_EOL;
			$this->_headerString .= 'return-path: <' . $this->_fromString . '>';
		}

		/* from and reply */

		$this->_headerString .= 'from: ' . $this->_fromString . PHP_EOL;
		$this->_headerString .= 'reply-to: ' . $this->_fromString . PHP_EOL;
	}

	/**
	 * send
	 *
	 * @since 1.3
	 */

	protected function _send()
	{
		foreach ($this->_toArray as $to => $toName)
		{
			/* fallback if numeric */

			if (is_numeric($to))
			{
				$to = $toName;
			}
			$toString = $to . ' <' . $toName . '>';

			/* send mail */

			mail($toString, $this->_subjectString, $this->_bodyString, $this->_headerString);
		}
	}
}
?>
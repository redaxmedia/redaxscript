<?php
namespace Redaxscript;

use SimpleXMLElement;
use function array_replace_recursive;
use function curl_close;
use function curl_exec;
use function curl_init;
use function curl_setopt;
use function curl_setopt_array;
use function file_get_contents;
use function function_exists;
use function is_array;
use function is_file;
use function is_numeric;
use function is_object;
use function json_decode;
use function json_encode;
use function method_exists;
use function simplexml_load_string;

/**
 * parent class to load and convert data
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Reader
 * @author Henry Ruhs
 */

class Reader
{
	/**
	 * options of the reader
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'curl' =>
		[
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_TIMEOUT_MS => 1000
		]
	];

	/**
	 * data object
	 *
	 * @var object
	 */

	protected $_dataObject;

	/**
	 * init the class
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray options of the messenger
	 *
	 * @return self
	 */

	public function init(array $optionArray = []) : self
	{
		$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		return $this;
	}

	/**
	 * get the object
	 *
	 * @since 3.0.0
	 *
	 * @return object|null
	 */

	public function getObject() : ?object
	{
		return $this->_dataObject ? : null;
	}

	/**
	 * get the array
	 *
	 * @since 3.1.0
	 *
	 * @return array|null
	 */

	public function getArray() : ?array
	{
		return json_decode(json_encode($this->_dataObject), true);
	}

	/**
	 * get the json
	 *
	 * @since 3.1.0
	 *
	 * @return string
	 */

	public function getJSON() : string
	{
		return json_encode($this->_dataObject);
	}

	/**
	 * get the xml
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function getXML() : string
	{
		$dataObject = $this->getObject();
		if (method_exists($dataObject, 'asXML'))
		{
			return $dataObject->asXML();
		}
		return $this->_convertArrayToObject($this->getArray())->asXML();
	}

	/**
	 * load the content from url
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 *
	 * @return string
	 */

	public function load(string $url = null) : string
	{
		/* remote curl */

		if (function_exists('curl_version') && !is_file($url))
		{
			$curl = curl_init();
			curl_setopt_array($curl, $this->_optionArray['curl']);
			curl_setopt($curl, CURLOPT_URL, $url);
			$output = curl_exec($curl);
			curl_close($curl);
		}

		/* else fallback */

		else
		{
			$output = file_get_contents($url);
		}
		return $output;
	}

	/**
	 * load the json from url
	 *
	 * @since 3.1.0
	 *
	 * @param string $url
	 *
	 * @return self
	 */

	public function loadJSON(string $url = null) : self
	{
		$content = $this->load($url);
		$this->_dataObject = json_decode($content);
		return $this;
	}

	/**
	 * load the xml from url
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 *
	 * @return self
	 */

	public function loadXML(string $url = null) : self
	{
		$content = $this->load($url);
		$this->_dataObject = simplexml_load_string($content);
		return $this;
	}

	/**
	 * convert array to object
	 *
	 * @since 3.1.0
	 *
	 * @param array $dataArray
	 * @param object $dataObject
	 *
	 * @return object
	 */

	protected function _convertArrayToObject(array $dataArray = [], object $dataObject = null) : object
	{
		if (!is_object($dataObject))
		{
			$dataObject = new SimpleXMLElement('<root />');
		}

		/* process data */

		foreach ($dataArray as $key => $value)
		{
			if (is_numeric($key))
			{
				$key = 'children';
			}
			if ($key === '@attributes')
			{
				foreach ($value as $attributeKey => $attributeValue)
				{
					$dataObject->addAttribute($attributeKey, $attributeValue);
				}
			}
			else if (is_array($value))
			{
				$this->_convertArrayToObject($value, $dataObject->addChild($key));
			}
			else
			{
				$dataObject->addChild($key, $value);
			}
		}
		return $dataObject;
	}
}

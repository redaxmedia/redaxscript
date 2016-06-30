<?php
namespace Redaxscript;

use SimpleXMLElement;

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
	 * data object
	 *
	 * @var object
	 */

	protected $_dataObject = array();

	/**
	 * data array
	 *
	 * @var array
	 */

	protected $_dataArray = array();

	/**
	 * assoc
	 *
	 * @var boolean
	 */

	protected $_assoc = true;

	/**
	 * get the object
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getObject()
	{
		if (!$this->_dataObject && $this->_dataArray)
		{
			$this->_dataObject = $this->_convertToObject();
		}
		return $this->_dataObject;
	}

	/**
	 * get the array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getArray()
	{
		if (!$this->_dataArray && $this->_dataObject)
		{
			$this->_dataArray = $this->_convertToArray();
		}
		return $this->_dataArray;
	}

	/**
	 * get the json
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function getJSON()
	{
		return json_encode($this->getArray());
	}

	/**
	 * get the xml
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public function getXML()
	{
		return $this->getObject()->asXML();
	}

	/**
	 * load json from url
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 * @param boolean $assoc
	 *
	 * @return Reader
	 */

	public function loadJSON($url = null, $assoc = true)
	{
		$contents = file_get_contents($url);
		$this->_assoc = $assoc;
		$this->_dataArray = json_decode($contents, $this->_assoc);
		return $this;
	}

	/**
	 * load xml from url
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 * @param boolean $assoc
	 *
	 * @return Reader
	 */

	public function loadXML($url = null, $assoc = true)
	{
		$contents = file_get_contents($url);
		$this->_assoc = $assoc;
		$this->_dataObject = simplexml_load_string($contents);
		return $this;
	}

	/**
	 * convert to object
	 *
	 * @since 3.0.0
	 *
	 * @return object
	 */

	protected function _convertToObject()
	{
		$dataObject = new SimpleXMLElement('<root />');
		array_walk_recursive($this->_dataArray, function($value, $key) use ($dataObject)
		{
			$dataObject->addChild($key, $value);
		});
		return $dataObject;
	}

	/**
	 * convert to array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	protected function _convertToArray()
	{
		return json_decode(json_encode($this->_dataObject), $this->_assoc);
	}
}

<?php
namespace Redaxscript;

use SimpleXMLElement;

/**
 * parent class to read data from url
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
	 * data array
	 *
	 * @var array
	 */

	protected $_dataArray = array();

	/**
	 * get the array
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getArray()
	{
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
		return json_encode($this->_dataArray);
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
		$element = new SimpleXMLElement('<root>');
		array_walk_recursive($this->_dataArray, array (
			$element,
			'addChild'
		));
		return $element->asXML();
	}

	/**
	 * fetch json from url
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 * @param boolean $assoc
	 *
	 * @return Reader
	 */

	public function fetchJSON($url = null, $assoc = true)
	{
		$contents = file_get_contents($url);
		$this->_dataArray = json_decode($contents, $assoc);
		return $this;
	}

	/**
	 * fetch xml from url
	 *
	 * @since 3.0.0
	 *
	 * @param string $url
	 *
	 * @return Reader
	 */

	public function fetchXML($url = null)
	{
		$contents = file_get_contents($url);
		$this->_dataArray = simplexml_load_string($contents);
		return $this;
	}
}

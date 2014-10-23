<?php
namespace Redaxscript;

/**
 * parent class to generate html
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Html
 * @author Henry Ruhs
 */

class Html
{
	private $tag = null;

	private $unaryTagArray = array(
		'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
		'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
	);

	private $attributeArray = array();

	private $innerHtml;

	public function __construct($tag = null, $attributeArray = array()) {
		$this->tag = strtolower($tag);

		foreach($attributeArray as $attribute => $value) {
			$this->attr($attribute, $value);
		}

		return $this;
	}

	function attr($attribute = null, $value = null) {
		if(!is_array($attribute)) {
			$this->attributeArray[$attribute] = trim($value);
		}
		else {
			$this->attributeArray = array_merge($this->attributeArray, $attribute);
		}

		return $this;
	}

	function removeAttr($attribute) {
		if(isset($this->attributeArray[$attribute])) {
			unset($this->attributeArray[$attribute]);
		}
		return $this;
	}

	function html($html) {
		$this->innerHtml = $html;

		return $this;
	}

	function text($text) {
		$this->innerHtml = strip_tags($text);

		return $this;
	}

	function _render() {
		// Start the tag

		$output = "<".$this->tag;

		// Add attributes
		if(is_array($this->attributeArray)) {
			foreach($this->attributeArray as $key => $value)
			{
				if ($value)
				{
					$output .= " ". $key . "=\"". $value . "\"";
				}
			}
		}

		// Close the element
		if(!in_array($this->tag, $this->unaryTagArray)) {
			$output.= ">" . $this->innerHtml . "</" . $this->tag . ">";
		}
		else {
			$output.= " />";
		}

		return $output;
	}

	function copy()
	{
		return clone $this;
	}

	function __toString()
	{
		return $this->_render();
	}
}
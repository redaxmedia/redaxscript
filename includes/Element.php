<?php
namespace Redaxscript;

/**
 * parent class to generate html
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Element
 * @author Henry Ruhs
 */

class Element
{
	/**
	 * tag of the element
	 *
	 * @var string
	 */

	protected $_tag = null;

	/**
	 * array of singleton tags
	 *
	 * @var array
	 */

	protected $_singletonTags = array(
		'area',
		'base',
		'br',
		'col',
		'command',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',
	);

	/**
	 * attributes of the element
	 *
	 * @var array
	 */

	protected $_attributeArray = array();

	/**
	 * html of the element
	 *
	 * @var string
	 */

	protected $_html;

	/**
	 * constructor of the class
	 *
	 * @since 2.2.0
	 *
	 * @param string $tag tag of the element
	 * @param array $attributeArray attributes of the element
	 *
	 * @return Element
	 */

	public function __construct($tag = null, $attributeArray = array())
	{
		$this->_tag = strtolower($tag);

		/* process attributes */

		foreach($attributeArray as $attribute => $value)
		{
			$this->attr($attribute, $value);
		}
		return $this;
	}

	/**
	 * stringify the element
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->_render();
	}

	/**
	 * copy the element
	 *
	 * @since 2.2.0
	 *
	 * @return Element
	 */

	public function copy()
	{
		return clone $this;
	}

	/**
	 * set attributes
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $attribute name or set of attributes
	 * @param array $value value of the attribute
	 *
	 * @return Element
	 */

	public function attr($attribute = null, $value = null)
	{
		if(is_array($attribute))
		{
			$this->_attributeArray = array_merge($this->_attributeArray, $attribute);
		}
		else
		{
			$this->_attributeArray[$attribute] = trim($value);
		}
		return $this;
	}

	/**
	 * remove attributes
	 *
	 * @since 2.2.0
	 *
	 * @param string $attribute name of attributes
	 *
	 * @return Element
	 */

	public function removeAttr($attribute = null)
	{
		if(isset($this->_attributeArray[$attribute]))
		{
			unset($this->_attributeArray[$attribute]);
		}
		return $this;
	}

	/**
	 * set html
	 *
	 * @since 2.2.0
	 *
	 * @param string $html html of the element
	 *
	 * @return Element
	 */

	public function html($html = null)
	{
		$this->_html  = $html;
		return $this;
	}

	/**
	 * set text
	 *
	 * @since 2.2.0
	 *
	 * @param string $text text of the element
	 *
	 * @return Element
	 */

	public function text($text = null)
	{
		$this->_html = strip_tags($text);
		return $this;
	}

	/**
	 * render the element
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */

	protected function _render()
	{
		$output = '<' . $this->_tag;

		/* process attributes */

		foreach($this->_attributeArray as $key => $value)
		{
			if ($value)
			{
				$output .= ' ' . $key . '="' . $value . '"';
			}
		}

		/* singleton tag */

		if(in_array($this->_tag, $this->_singletonTags))
		{
			$output .= ' />';

		}

		/* else normal tag */

		else
		{
			$output .= '>' . $this->_html . '</' . $this->_tag . '>';
		}
		return $output;
	}

}
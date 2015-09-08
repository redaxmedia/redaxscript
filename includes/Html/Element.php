<?php
namespace Redaxscript\Html;

/**
 * children class to generate a element
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Element
 * @author Henry Ruhs
 */

class Element extends HtmlAbstract
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

		if (is_array($attributeArray))
		{
			foreach ($attributeArray as $attribute => $value)
			{
				$this->attr($attribute, $value);
			}
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
		return $this->render();
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
	 * set attribute to the element
	 *
	 * @since 2.2.0
	 *
	 * @param mixed $attribute name or set of attributes
	 * @param string $value value of the attribute
	 *
	 * @return Element
	 */

	public function attr($attribute = null, $value = null)
	{
		if (is_array($attribute))
		{
			$this->_attributeArray = array_merge($this->_attributeArray, $attribute);
		}
		else if (is_string($attribute) && is_string($value))
		{
			$this->_attributeArray[$attribute] = trim($value);
		}
		return $this;
	}

	/**
	 * remove attribute from the element
	 *
	 * @since 2.2.0
	 *
	 * @param string $attribute name of attributes
	 *
	 * @return Element
	 */

	public function removeAttr($attribute = null)
	{
		if (isset($this->_attributeArray[$attribute]))
		{
			unset($this->_attributeArray[$attribute]);
		}
		return $this;
	}

	/**
	 * add class to the element
	 *
	 * @since 2.2.0
	 *
	 * @param string $className name of the classes
	 *
	 * @return Element
	 */

	public function addClass($className = null)
	{
		$this->_editClass($className, 'add');
		return $this;
	}

	/**
	 * remove class from the element
	 *
	 * @since 2.2.0
	 *
	 * @param string $className name of the classes
	 *
	 * @return Element
	 */

	public function removeClass($className = null)
	{
		$this->_editClass($className, 'remove');
		return $this;
	}

	/**
	 * edit class helper
	 *
	 * @since 2.2.0
	 *
	 * @param string $className name of the classes
	 * @param string $type add or remove
	 *
	 * @return Element
	 */

	protected function _editClass($className = null, $type = null)
	{
		$classArray = array_filter(explode(' ', $className));
		if (isset($this->_attributeArray['class']))
		{
			$attributeClassArray = array_filter(explode(' ', $this->_attributeArray['class']));
		}
		else
		{
			$attributeClassArray = array();
		}

		/* add or remove */

		if (is_array($attributeClassArray) && is_array($classArray))
		{
			if ($type === 'add')
			{
				$this->_attributeArray['class'] = implode(' ', array_merge($attributeClassArray, $classArray));
			}
			else if ($type === 'remove')
			{
				$this->_attributeArray['class'] = implode(' ', array_diff($attributeClassArray, $classArray));
			}
		}
	}

	/**
	 * set value to the element
	 *
	 * @since 2.2.0
	 *
	 * @param string $value value of the element
	 *
	 * @return Element
	 */

	public function val($value = null)
	{
		$this->_attributeArray['value'] = trim($value);
		return $this;
	}

	/**
	 * set text to the element
	 *
	 * @since 2.2.0
	 *
	 * @param string $text text of the element
	 *
	 * @return Element
	 */

	public function text($text = null)
	{
		if (strip_tags($text))
		{
			$this->_html = strip_tags($text);
		}
		return $this;
	}

	/**
	 * render the element
	 *
	 * @since 2.2.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = '<' . $this->_tag;

		/* process attributes */

		foreach ($this->_attributeArray as $key => $value)
		{
			if (is_string($key) && $value)
			{
				$output .= ' ' . $key . '="' . $value . '"';
			}
		}

		/* singleton tag */

		if (in_array($this->_tag, $this->_singletonTags))
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

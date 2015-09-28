<?php
namespace Redaxscript\Html;

use Redaxscript\Captcha;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to generate a form
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Html
 * @author Henry Ruhs
 */

class Form extends HtmlAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * captcha of the form
	 *
	 * @var object
	 */

	protected $_captcha;

	/**
	 * attributes of the form
	 *
	 * @var array
	 */

	protected $_attributeArray = array(
		'form' => array(
			'class' => 'js-validate-form form-default',
			'method' => 'post'
		),
		'label' => array(
			'class' => 'label-default'
		),
		'number' => array(
			'class' => 'field-number',
			'type' => 'number'
		),
		'input' => array(
			'class' => 'field-text',
			'type' => 'text'
		),
		'select' => array(
			'class' => 'field-select'
		),
		'textarea' => array(
			'class' => 'field-textarea',
			'cols' => 100,
			'row' => 5
		),
		'submit' => array(
			'class' => 'js-button button-default',
			'type' => 'submit'
		),
		'reset' => array(
			'class' => 'js-reset button-default',
			'type' => 'reset'
		),
		'button' => array(
			'class' => 'js-button button-default',
			'type' => 'button'
		)
	);

	/**
	 * options of the form
	 *
	 * @var array
	 */

	protected $_options = array(
		'captcha' => true
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.6.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * stringify the element
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function __toString()
	{
		return $this->render();
	}

	/**
	 * init the class
	 *
	 * @since 2.6.0
	 *
	 * @param array $attributeArray attributes of the form
	 * @param array $options options of the form
	 */

	public function init($attributeArray = array(), $options = null)
	{
		if (is_array($attributeArray))
		{
			$this->_attributeArray = array_replace_recursive($this->_attributeArray, $attributeArray);
		}
		if (is_array($options))
		{
			$this->_options = array_merge($this->_options, $options);
		}

		/* captcha */

		if ($this->_options['captcha'])
		{
			$this->_captcha = new Captcha($this->_language->getInstance());
			$this->_captcha->init();
		}
	}

	/**
	 * append the label
	 *
	 * @since 2.6.0
	 *
	 * @param string $text text of the label
	 * @param array $attributeArray attributes of the input
	 *
	 * @return Form
	 */

	public function label($text = null, $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['label'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['label'];
		}
		$labelElement = new Element('label', $attributeArray);
		$labelElement->text($text);
		$this->append($labelElement);
	}

	/**
	 * append the number
	 *
	 * @since 2.6.0
	 *
	 * @param array $attributeArray attributes of the number
	 *
	 * @return Form
	 */

	public function number($attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['number'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['number'];
		}
		return $this->input($attributeArray);
	}

	/**
	 * append the input
	 *
	 * @since 2.6.0
	 *
	 * @param array $attributeArray attributes of the input
	 *
	 * @return Form
	 */

	public function input($attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['input'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['input'];
		}
		$inputElement = new Element('input', $attributeArray);
		$this->append($inputElement);
	}

	/**
	 * append the captcha
	 *
	 * @since 2.6.0
	 *
	 * @param string $type type of the captcha
	 *
	 * @return Form
	 */

	public function captcha($type = null)
	{
		/* task */

		if ($type === 'task' || is_null($type))
		{
			$labelElement = $this->label($this->_captcha->getTask(), array(
				'for' => 'task'
			));
			$taskElement = $this->number(array(
				'id' => 'task',
				'min' => $this->_captcha->getMin(),
				'max' => $this->_captcha->getMax(),
				'name' => 'task',
				'required' => 'required'
			));
			$this->append($labelElement . $taskElement);
		}

		/* solution */

		if ($type === 'solution' || is_null($type))
		{
			$solutionElement = new Element('input', array(
				'name' => 'solution',
				'type' => 'hidden',
				'value' => $this->_captcha->getSolution()
			));
			$this->append($solutionElement);
		}
		return $this;
	}

	/**
	 * append the token
	 *
	 * @since 2.6.0
	 *
	 * @return Form
	 */

	public function token()
	{
		$token = $this->_registry->get('token');
		if ($token)
		{
			$tokenElement = new Element('input', array(
				'name' => 'token',
				'type' => 'hidden',
				'value' => $token
			));
			$this->append($tokenElement);
		}
		return $this;
	}

	/**
	 * append the submit
	 *
	 * @since 2.6.0
	 *
	 * @param string $text text of the submit
	 * @param array $attributeArray attributes of the submit
	 *
	 * @return Form
	 */

	public function submit($text = null, $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['submit'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['submit'];
		}
		return $this->button($text ? $text : $this->_language->get('submit'), $attributeArray);
	}

	/**
	 * append the reset
	 *
	 * @since 2.6.0
	 *
	 * @param string $text text of the reset
	 * @param array $attributeArray attributes of the reset
	 *
	 * @return Form
	 */

	public function reset($text = null, $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['reset'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['reset'];
		}
		return $this->button($text ? $text : $this->_language->get('reset'), $attributeArray);
	}

	/**
	 * append the button
	 *
	 * @since 2.6.0
	 *
	 * @param string $text text of the button
	 * @param array $attributeArray attributes of the button
	 *
	 * @return Form
	 */

	public function button($text = null, $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['button'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['button'];
		}
		$buttonElement = new Element('button', $attributeArray);
		$buttonElement->text($text ? $text : $this->_language->get('ok'));
		$this->append($buttonElement);
		return $this;
	}

	/**
	 * render the form
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = Hook::trigger('form_start');
		$formElement = new Element('form', $this->_attributeArray['form']);

		/* collect output */

		$output .= $formElement->html($this->_html);
		$output .= Hook::trigger('form_end');
		return $output;
	}
}

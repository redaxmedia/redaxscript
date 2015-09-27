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
		'text' => array(
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
			$this->_attributeArray = array_unique(array_merge($this->_attributeArray, $attributeArray));
		}
		if (is_array($options))
		{
			$this->_options = array_unique(array_merge($this->_options, $options));
		}

		/* captcha */

		if ($this->_options['captcha'])
		{
			$this->_captcha = new Captcha($this->_language->getInstance());
			$this->_captcha->init();
		}
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

		if ($type === 'task')
		{
			$labelElement = new Element('label', array(
				'class' => $this->_attributeArray['label']['class'],
				'for' => 'task'
			));
			$labelElement->text($this->_captcha->getTask());
			$taskElement = new Element('input', array(
				'class' => $this->_attributeArray['text']['class'],
				'id' => 'task',
				'min' => $this->_captcha->getMin(),
				'max' => $this->_captcha->getMax(),
				'name' => 'task',
				'required' => 'required',
				'type' => 'number'
			));
			$this->append($labelElement . $taskElement);
		}

		/* solution */

		if ($type === 'solution')
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
		if ($attributeArray)
		{
			$attributeArray = array_unique(array_merge($this->_attributeArray['submit'], $attributeArray));
		}
		return $this->button($text, $attributeArray);
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
		if ($attributeArray)
		{
			$attributeArray = array_unique(array_merge($this->_attributeArray['reset'], $attributeArray));
		}
		return $this->button($text, $attributeArray);
	}

	/**
	 * append the button
	 *
	 * @since 2.6.0
	 *
	 * @param string $type type of the button
	 * @param array $attributeArray attributes of the button
	 *
	 * @return Form
	 */

	public function button($text = null, $attributeArray = array())
	{
		if ($attributeArray)
		{
			$attributeArray = array_unique(array_merge($this->_attributeArray['button'], $attributeArray));
		}
		$buttonElement = new Element('button', $attributeArray);
		$buttonElement->text(is_null($text) ? $this->_language->get($attributeArray['type']) : $text);
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
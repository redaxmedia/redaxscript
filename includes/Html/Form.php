<?php
namespace Redaxscript\Html;

use Redaxscript\Captcha;
use Redaxscript\Config;
use Redaxscript\Hash;
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
*
 * @method button()
 * @method checkbox()
 * @method datetime()
 * @method email()
 * @method file()
 * @method hidden()
 * @method number()
 * @method password()
 * @method radio()
 * @method range()
 * @method reset()
 * @method search()
 * @method submit()
 * @method tel()
 * @method text()
 * @method url()
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
	 * language of the form
	 *
	 * @var array
	 */

	protected $_languageArray = array(
		'legend' => 'fields_required',
		'button' => array(
			'button' => 'ok',
			'submit' => 'submit',
			'reset' => 'reset'
		)
	);

	/**
	 * attributes of the form
	 *
	 * @var array
	 */

	protected $_attributeArray = array(
		'form' => array(
			'class' => 'rs-js-validate-form rs-form-default',
			'method' => 'post'
		),
		'legend' => array(
			'class' => 'rs-legend-default'
		),
		'label' => array(
			'class' => 'rs-label-default'
		),
		'select' => array(
			'class' => 'rs-field-select'
		),
		'textarea' => array(
			'class' => 'rs-field-textarea',
			'cols' => 100,
			'row' => 5
		),
		'button' => array(
			'button' => array(
				'class' => 'rs-js-button rs-button-default',
				'type' => 'button'
			),
			'reset' => array(
				'class' => 'rs-js-reset rs-button-default',
				'type' => 'reset',
				'value' => 'reset'
			),
			'submit' => array(
				'class' => 'rs-js-button rs-button-default',
				'type' => 'submit',
				'value' => 'submit'
			)
		),
		'input' => array(
			'checkbox' => array(
				'class' => 'rs-field-checkbox',
				'type' => 'checkbox'
			),
			'datetime' => array(
				'class' => 'rs-field-default rs-field-date',
				'type' => 'datetime'
			),
			'email' => array(
				'class' => 'rs-field-default rs-field-email',
				'type' => 'email'
			),
			'file' => array(
				'class' => 'rs-field-file',
				'type' => 'file'
			),
			'hidden' => array(
				'class' => 'rs-field-hidden',
				'type' => 'hidden'
			),
			'number' => array(
				'class' => 'rs-field-default rs-field-number',
				'type' => 'number'
			),
			'password' => array(
				'class' => 'rs-field-default rs-field-password',
				'type' => 'password'
			),
			'radio' => array(
				'class' => 'rs-field-radio',
				'type' => 'radio'
			),
			'range' => array(
				'class' => 'rs-field-range',
				'type' => 'range'
			),
			'search' => array(
				'class' => 'rs-js-search rs-field-search',
				'type' => 'search'
			),
			'tel' => array(
				'class' => 'rs-field-default rs-field-tel',
				'type' => 'tel'
			),
			'text' => array(
				'class' => 'rs-field-default rs-field-text',
				'type' => 'text'
			),
			'url' => array(
				'class' => 'rs-field-default rs-field-url',
				'type' => 'url'
			)
		)
	);

	/**
	 * options of the form
	 *
	 * @var array
	 */

	protected $_options = array(
		'captcha' => false
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
	 * call method as needed
	 *
	 * @since 2.6.0
	 *
	 * @param string $method name of the method
	 * @param array $arguments arguments of the method
	 *
	 * @return Form
	 */

	public function __call($method = null, $arguments = array())
	{
		/* button */

		if (array_key_exists($method, $this->_attributeArray['button']))
		{
			return $this->_createButton($method, $arguments[0], $arguments[1]);
		}

		/* input */

		if (array_key_exists($method, $this->_attributeArray['input']))
		{
			return $this->_createInput($method, $arguments[0]);
		}
	}

	/**
	 * stringify the form
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
	 *
	 * @return Form
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
		return $this;
	}

	/**
	 * append the legend
	 *
	 * @since 3.0.0
	 *
	 * @param string $html html of the legend
	 * @param array $attributeArray attributes of the legend
	 *
	 * @return Form
	 */

	public function legend($html = null, $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['legend'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['legend'];
		}
		$legendElement = new Element();
		$legendElement
			->init('legend', $attributeArray)
			->html($html ? $html : $this->_language->get($this->_languageArray['legend']) . $this->_language->get('point'));
		$this->append($legendElement);
		return $this;
	}

	/**
	 * append the label
	 *
	 * @since 3.0.0
	 *
	 * @param string $html html of the label
	 * @param array $attributeArray attributes of the label
	 *
	 * @return Form
	 */

	public function label($html = null, $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['label'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['label'];
		}
		$labelElement = new Element();
		$labelElement
			->init('label', $attributeArray)
			->html($html);
		$this->append($labelElement);
		return $this;
	}

	/**
	 * append the textarea
	 *
	 * @since 2.6.0
	 *
	 * @param array $attributeArray attributes of the textarea
	 *
	 * @return Form
	 */

	public function textarea($attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['textarea'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['textarea'];
		}
		$textareaElement = new Element();
		$textareaElement
			->init('textarea', $attributeArray)
			->text($attributeArray['value'])
			->val(null);
		$this->append($textareaElement);
		return $this;
	}

	/**
	 * append the select
	 *
	 * @since 2.6.0
	 *
	 * @param array $optionArray options of the select
	 * @param array $attributeArray attributes of the select
	 *
	 * @return Form
	 */

	public function select($optionArray = array(), $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['select'], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['select'];
		}
		$selectElement = new Element();
		$selectElement
			->init('select', $attributeArray)
			->html($this->_createOption($optionArray, $attributeArray['value']))
			->val(null);
		$this->append($selectElement);
		return $this;
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

		if ($this->_options['captcha'] && $type === 'task')
		{
			$this->label('* ' . $this->_captcha->getTask(), array(
				'for' => 'task'
			));

			/* number */

			$this->number(array(
				'id' => 'task',
				'min' => $this->_captcha->getMin(),
				'max' => $this->_captcha->getMax() * 2,
				'name' => 'task',
				'required' => 'required'
			));
		}

		/* solution */

		if ($this->_options['captcha'] && $type === 'solution')
		{
			$captchaHash = new Hash(Config::getInstance());
			$captchaHash->init($this->_captcha->getSolution());

			/* hidden */

			$this->hidden(array(
				'name' => 'solution',
				'value' => $captchaHash->getHash()
			));
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
			$this->hidden(array(
				'name' => 'token',
				'value' => $token
			));
		}
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
		$output = Hook::trigger('formStart');
		$formElement = new Element();
		$formElement->init('form', $this->_attributeArray['form']);

		/* collect output */

		$output .= $formElement->html($this->_html);
		$output .= Hook::trigger('formEnd');
		return $output;
	}

	/**
	 * create the button
	 *
	 * @since 2.6.0
	 *
	 * @param string $type type of the button
	 * @param string $text text of the button
	 * @param array $attributeArray attributes of the button
	 *
	 * @return Form
	 */

	protected function _createButton($type = null, $text = null, $attributeArray = array())
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['button'][$type], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['button'][$type];
		}
		$buttonElement = new Element();
		$buttonElement
			->init('button', $attributeArray)
			->text($text ? $text : $this->_language->get($this->_languageArray['button'][$type]));
		$this->append($buttonElement);
		return $this;
	}

	/**
	 * create the option
	 *
	 * @since 2.6.0
	 *
	 * @param array $optionArray options of the select
	 * @param string $selected option to be selected
	 *
	 * @return string
	 */

	protected function _createOption($optionArray = array(), $selected = null)
	{
		$output = null;
		$optionElement = new Element();
		$optionElement->init('option');

		/* process option */

		foreach ($optionArray as $key => $value)
		{
			$output .= $optionElement
				->copy()
				->attr(array(
					'selected' => $value === $selected ? 'selected' : null,
					'value' => $value
				))
				->text(is_string($key) ? $key : null);
		}
		return $output;
	}

	/**
	 * create the input
	 *
	 * @since 2.6.0
	 *
	 * @param string $type type of the input
	 * @param array $attributeArray attributes of the input
	 *
	 * @return Form
	 */

	protected function _createInput($type = 'text', $attributeArray = array())
	{
		if (is_array($attributeArray))
		{

			$attributeArray = array_merge($this->_attributeArray['input'][$type], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['input'][$type];
		}
		$inputElement = new Element();
		$inputElement->init('input', $attributeArray);
		$this->append($inputElement);
		return $this;
	}
}

<?php
namespace Redaxscript\Html;

use Redaxscript\Captcha;
use Redaxscript\Config;
use Redaxscript\Hash;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;

/**
 * children class to create a form
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Server
 * @author Henry Ruhs
 *
 * @method button(string $text = null, array $attributeArray = [])
 * @method cancel(string $text = null, array $attributeArray = [])
 * @method checkbox(array $attributeArray = [])
 * @method color(array $attributeArray = [])
 * @method date(array $attributeArray = [])
 * @method datetime(array $attributeArray = [])
 * @method email(array $attributeArray = [])
 * @method file(array $attributeArray = [])
 * @method hidden(array $attributeArray = [])
 * @method number(array $attributeArray = [])
 * @method password(array $attributeArray = [])
 * @method radio(array $attributeArray = [])
 * @method range(array $attributeArray = [])
 * @method reset(string $text = null, array $attributeArray = [])
 * @method search(array $attributeArray = [])
 * @method submit(string $text = null, array $attributeArray = [])
 * @method time(array $attributeArray = [])
 * @method tel(array $attributeArray = [])
 * @method text(array $attributeArray = [])
 * @method url(array $attributeArray = [])
 * @method week(array $attributeArray = [])
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
	 * languages of the form
	 *
	 * @var array
	 */

	protected $_languageArray = [
		'legend' => 'fields_required',
		'button' =>
		[
			'button' => 'ok',
			'reset' => 'reset',
			'submit' => 'submit'
		],
		'link' =>
		[
			'cancel' => 'cancel'
		]
	];

	/**
	 * attributes of the form
	 *
	 * @var array
	 */

	protected $_attributeArray = [
		'form' =>
		[
			'class' => 'rs-js-validate-form rs-form-default',
			'method' => 'post'
		],
		'legend' =>
		[
			'class' => 'rs-legend-default'
		],
		'label' =>
		[
			'class' => 'rs-label-default'
		],
		'select' =>
		[
			'class' => 'rs-field-select'
		],
		'textarea' =>
		[
			'class' => 'rs-field-textarea',
			'cols' => 100,
			'rows' => 5
		],
		'input' =>
		[
			'checkbox' =>
			[
				'class' => 'rs-field-checkbox',
				'type' => 'checkbox'
			],
			'color' =>
			[
				'class' => 'rs-field-color',
				'type' => 'color'
			],
			'date' =>
			[
				'class' => 'rs-field-default rs-field-date',
				'type' => 'date'
			],
			'datetime' =>
			[
				'class' => 'rs-field-default rs-field-date',
				'type' => 'datetime-local'
			],
			'email' =>
			[
				'class' => 'rs-field-default rs-field-email',
				'type' => 'email'
			],
			'file' =>
			[
				'class' => 'rs-field-file',
				'type' => 'file'
			],
			'hidden' =>
			[
				'class' => 'rs-field-hidden',
				'type' => 'hidden'
			],
			'number' =>
			[
				'class' => 'rs-field-default rs-field-number',
				'type' => 'number'
			],
			'password' =>
			[
				'class' => 'rs-js-unmask-password rs-field-default rs-field-password',
				'type' => 'password'
			],
			'radio' =>
			[
				'class' => 'rs-field-radio',
				'type' => 'radio'
			],
			'range' =>
			[
				'class' => 'rs-field-range',
				'type' => 'range'
			],
			'search' =>
			[
				'class' => 'rs-js-search rs-field-search',
				'type' => 'search'
			],
			'tel' =>
			[
				'class' => 'rs-field-default rs-field-tel',
				'type' => 'tel'
			],
			'time' =>
			[
				'class' => 'rs-field-default rs-field-date',
				'type' => 'time'
			],
			'text' =>
			[
				'class' => 'rs-field-default rs-field-text',
				'type' => 'text'
			],
			'url' =>
			[
				'class' => 'rs-field-default rs-field-url',
				'type' => 'url'
			],
			'week' =>
			[
				'class' => 'rs-field-default rs-field-date',
				'type' => 'week'
			]
		],
		'button' =>
		[
			'button' =>
			[
				'class' => 'rs-js-button rs-button-default',
				'type' => 'button'
			],
			'reset' =>
			[
				'class' => 'rs-js-reset rs-button-default rs-button-reset',
				'type' => 'reset'
			],
			'submit' =>
			[
				'class' => 'rs-js-button rs-button-default rs-button-submit',
				'type' => 'submit',
				'value' => 'submit'
			]
		],
		'link' =>
		[
			'cancel' =>
			[
				'class' => 'rs-js-cancel rs-button-default rs-button-cancel',
				'href' => 'javascript:history.back()'
			]
		]
	];

	/**
	 * options of the form
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'captcha' => false
	];

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
	 * @param array $argumentArray arguments of the method
	 *
	 * @return Form
	 */

	public function __call($method = null, $argumentArray = [])
	{
		/* input */

		if (array_key_exists($method, $this->_attributeArray['input']))
		{
			return $this->_createInput($method, $argumentArray[0]);
		}

		/* button */

		if (array_key_exists($method, $this->_attributeArray['button']))
		{
			return $this->_createButton($method, $argumentArray[0], $argumentArray[1]);
		}

		/* link */

		if (array_key_exists($method, $this->_attributeArray['link']))
		{
			return $this->_createLink($method, $argumentArray[0], $argumentArray[1]);
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
	 * @param array $optionArray options of the form
	 *
	 * @return Form
	 */

	public function init($attributeArray = [], $optionArray = [])
	{
		if (is_array($attributeArray))
		{
			$this->_attributeArray = array_replace_recursive($this->_attributeArray, $attributeArray);
		}
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}

		/* captcha */

		if ($this->_optionArray['captcha'])
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

	public function legend($html = null, $attributeArray = [])
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

	public function label($html = null, $attributeArray = [])
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

	public function textarea($attributeArray = [])
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

	public function select($optionArray = [], $attributeArray = [])
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
	 * append the select range
	 *
	 * @since 3.0.0
	 *
	 * @param array $rangeArray range of the select
	 * @param array $attributeArray attributes of the select
	 *
	 * @return Form
	 */

	public function selectRange($rangeArray = [], $attributeArray = [])
	{
		$this->select(range($rangeArray['min'], $rangeArray['max']), $attributeArray);
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

		if ($this->_optionArray['captcha'] && $type === 'task')
		{
			$this->label('* ' . $this->_captcha->getTask(),
			[
				'for' => 'task'
			]);

			/* number */

			$this->number(
			[
				'id' => 'task',
				'min' => $this->_captcha->getMin(),
				'max' => $this->_captcha->getMax() * 2,
				'name' => 'task',
				'required' => 'required'
			]);
		}

		/* solution */

		if ($this->_optionArray['captcha'] && $type === 'solution')
		{
			$captchaHash = new Hash(Config::getInstance());
			$captchaHash->init($this->_captcha->getSolution());

			/* hidden */

			$this->hidden(
			[
				'name' => 'solution',
				'value' => $captchaHash->getHash()
			]);
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
			$this->hidden(
			[
				'name' => 'token',
				'value' => $token
			]);
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
	 * create the input
	 *
	 * @since 2.6.0
	 *
	 * @param string $type type of the input
	 * @param array $attributeArray attributes of the input
	 *
	 * @return Form
	 */

	protected function _createInput($type = 'text', $attributeArray = [])
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

	/**
	 * create the option
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray options of the select
	 * @param mixed $selected option to be selected
	 *
	 * @return string
	 */

	protected function _createOption($optionArray = [], $selected = null)
	{
		$output = null;
		$optionElement = new Element();
		$optionElement->init('option');

		/* handle selected */

		if (is_string($selected))
		{
			$selected = explode(', ', $selected);
		}

		/* process options */

		foreach ($optionArray as $key => $value)
		{
			if ($key || $value)
			{
				$output .= $optionElement
					->copy()
					->attr(
					[
						'selected' => $value === $selected || in_array($value, $selected) ? 'selected' : null,
						'value' => $value
					])
					->text(is_string($key) ? $key : $value);
			}
		}
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

	protected function _createButton($type = null, $text = null, $attributeArray = [])
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
	 * create the link
	 *
	 * @since 3.0.0
	 *
	 * @param string $type type of the link
	 * @param string $text text of the link
	 * @param array $attributeArray attributes of the link
	 *
	 * @return Form
	 */

	protected function _createLink($type = null, $text = null, $attributeArray = [])
	{
		if (is_array($attributeArray))
		{
			$attributeArray = array_merge($this->_attributeArray['link'][$type], $attributeArray);
		}
		else
		{
			$attributeArray = $this->_attributeArray['link'][$type];
		}
		$linkElement = new Element();
		$linkElement
			->init('a', $attributeArray)
			->text($text ? $text : $this->_language->get($this->_languageArray['link'][$type]));
		$this->append($linkElement);
		return $this;
	}
}

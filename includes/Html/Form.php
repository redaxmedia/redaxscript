<?php
namespace Redaxscript\Html;

use Redaxscript\Captcha;
use Redaxscript\Hash;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * children class to create a form
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Html
 * @author Henry Ruhs
 *
 * @method $this button(string $text = null, array $attributeArray = [])
 * @method $this cancel(string $text = null, array $attributeArray = [])
 * @method $this checkbox(array $attributeArray = [])
 * @method $this color(array $attributeArray = [])
 * @method $this date(array $attributeArray = [])
 * @method $this datetime(array $attributeArray = [])
 * @method $this email(array $attributeArray = [])
 * @method $this file(array $attributeArray = [])
 * @method $this hidden(array $attributeArray = [])
 * @method $this number(array $attributeArray = [])
 * @method $this password(array $attributeArray = [])
 * @method $this radio(array $attributeArray = [])
 * @method $this range(array $attributeArray = [])
 * @method $this reset(string $text = null, array $attributeArray = [])
 * @method $this search(array $attributeArray = [])
 * @method $this submit(string $text = null, array $attributeArray = [])
 * @method $this time(array $attributeArray = [])
 * @method $this tel(array $attributeArray = [])
 * @method $this text(array $attributeArray = [])
 * @method $this url(array $attributeArray = [])
 * @method $this week(array $attributeArray = [])
 */

class Form extends HtmlAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var Language
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

	protected $_languageArray =
	[
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

	protected $_attributeArray =
	[
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
				'class' => 'rs-field-default rs-field-password',
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
	 * @return self
	 */

	public function __call(string $method = null, array $argumentArray = []) : self
	{
		/* input */

		if (is_array($this->_attributeArray['input']) && array_key_exists($method, $this->_attributeArray['input']))
		{
			return $this->_createInput($method, $argumentArray[0]);
		}

		/* button */

		if (is_array($this->_attributeArray['button']) && array_key_exists($method, $this->_attributeArray['button']))
		{
			return $this->_createButton($method, $argumentArray[0], $argumentArray[1]);
		}

		/* link */

		if (is_array($this->_attributeArray['link']) && array_key_exists($method, $this->_attributeArray['link']))
		{
			return $this->_createLink($method, $argumentArray[0], $argumentArray[1]);
		}
		return $this;
	}

	/**
	 * stringify the form
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function __toString() : string
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
	 * @return self
	 */

	public function init($attributeArray = [], array $optionArray = []) : self
	{
		if (is_array($attributeArray))
		{
			$this->_attributeArray = array_replace_recursive($this->_attributeArray, $attributeArray);
		}
		if (is_array($optionArray))
		{
			$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
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
	 * @return self
	 */

	public function legend(string $html = null, array $attributeArray = []) : self
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
	 * @return self
	 */

	public function label(string $html = null, array $attributeArray = []) : self
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
	 * @return self
	 */

	public function textarea(array $attributeArray = []) : self
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
	 * @param array $optionArray option of the select
	 * @param array $selectArray values to be selected
	 * @param array $attributeArray attributes of the select
	 *
	 * @return self
	 */

	public function select(array $optionArray = [], array $selectArray = [], array $attributeArray = []) : self
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
			->html($this->_createOption($optionArray, $selectArray));
		$this->append($selectElement);
		return $this;
	}

	/**
	 * append the select range
	 *
	 * @since 3.0.0
	 *
	 * @param array $rangeArray range of the select
	 * @param array $selectArray values to be selected
	 * @param array $attributeArray attributes of the select
	 *
	 * @return self
	 */

	public function selectRange(array $rangeArray = [], array $selectArray = [], array $attributeArray = []) : self
	{
		$this->select(range($rangeArray['min'], $rangeArray['max']), $selectArray, $attributeArray);
		return $this;
	}

	/**
	 * append the captcha
	 *
	 * @since 2.6.0
	 *
	 * @param string $type type of the captcha
	 *
	 * @return self
	 */

	public function captcha(string $type = null) : self
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
			$captchaHash = new Hash();
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
	 * @return self
	 */

	public function token() : self
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

	public function render() : string
	{
		$output = Module\Hook::trigger('formStart');
		$formElement = new Element();
		$formElement->init('form', $this->_attributeArray['form']);

		/* collect output */

		$output .= $formElement->html($this->_html);
		$output .= Module\Hook::trigger('formEnd');
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
	 * @return self
	 */

	protected function _createInput(string $type = 'text', array $attributeArray = []) : self
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
	 * @param array $optionArray option of the select
	 * @param array $selectArray values to be selected
	 *
	 * @return string|null
	 */

	protected function _createOption(array $optionArray = [], array $selectArray = []) : ?string
	{
		$output = null;
		$optionElement = new Element();
		$optionElement->init('option');

		/* process values */

		if (is_array($optionArray))
		{
			foreach ($optionArray as $key => $value)
			{
				if ($key || $value)
				{
					$output .= $optionElement
						->copy()
						->attr(
						[
							'selected' => is_array($selectArray) && in_array($value, $selectArray) ? 'selected' : null,
							'value' => $value
						])
						->text(is_string($key) ? $key : $value);
				}
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
	 * @return self
	 */

	protected function _createButton(string $type = null, string $text = null, $attributeArray = []) : self
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
	 * @return self
	 */

	protected function _createLink(string $type = null, string $text = null, $attributeArray = []) : self
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

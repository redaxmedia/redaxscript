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
	 * options of the form
	 *
	 * @var array
	 */

	protected $_options = array(
		'className' => array(
			'form' => 'js-validate-form form-default',
			'field' => 'field-default',
			'label' => 'label-default',
			'button' => 'js-button button-default',
			'submit' => 'js-submit button-default',
			'reset' => 'js-reset button-default'
		),
		'action' => '',
		'method' => 'post',
		'name' => ''
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
	 * @param array $options options of the form
	 */

	public function init($options = null)
	{
		if (is_array($options))
		{
			$this->_options = array_unique(array_merge($this->_options, $options));
		}
	}

	/**
	 * append the captcha
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function captcha()
	{
		/* captcha */

		$captcha = new Captcha($this->_language->getInstance());
		$captcha->init();

		/* task */

		$labelElement = new Element('label', array(
			'class' => $this->_options['className']['label'],
			'for' => 'task'
		));
		$labelElement->text($captcha->getTask());
		$taskElement = new Element('input', array(
			'class' => $this->_options['className']['field'],
			'id' => 'task',
			'min' => $captcha->getMin(),
			'max' => $captcha->getMax(),
			'name' => 'task',
			'required' => 'required',
			'type' => 'number'
		));
		$this->append($labelElement . $taskElement);

		/* solution */

		$solutionElement = new Element('input', array(
			'name' => 'solution',
			'type' => 'hidden',
			'value' => $captcha->getSolution()
		));
		$this->append($solutionElement);
		return $this;
	}

	/**
	 * append the token
	 *
	 * @since 2.6.0
	 *
	 * @return string
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
	 * @return string
	 */

	public function submit()
	{
		return $this->button('submit');
	}

	/**
	 * append the reset
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function reset()
	{
		return $this->button('reset');
	}

	/**
	 * append the button
	 *
	 * @since 2.6.0
	 *
	 * @param string $type type of the button
	 *
	 * @return string
	 */

	public function button($type = 'button')
	{
		$buttonElement = new Element('button', array(
			'class' => $this->_options['className'][$type],
			'type' => $type
		));
		$buttonElement->text(Language::get($type));
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
		$formElement = new Element('form', array(
			'action' => $this->_options['action'],
			'class' => $this->_options['className']['form'],
			'method' => $this->_options['method'],
			'name' => $this->_options['name']
		));

		/* collect output */

		$output .= $formElement->html($this->_html);
		$output .= Hook::trigger('form_end');
		return $output;
	}
}
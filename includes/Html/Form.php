<?php
namespace Redaxscript\Html;

use Redaxscript\Captcha;
use Redaxscript\Hook;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;

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
			'captcha' => array(
				'field' => 'field_text field_note',
				'label' => 'label_default'
			)
		),
		'method' => 'post',
		'action' => '',
		'captcha' => false,
		'token' => true
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
	 * render the form
	 *
	 * @since 2.6.0
	 *
	 * @return string
	 */

	public function render()
	{
		$output = Hook::trigger('form_start');
		$token = $this->_registry->get('token');

		/* html elements */

		$formElement = new Element('form', array(
			'method' => $this->_options['method'],
			'action' => $this->_options['action'],
			'class' => $this->_options['className']['form']
		));
		$formElement->html($this->_html);

		/* captcha task */

		if ($this->_options['captcha'])
		{
			$captcha = new Captcha($this->_language->getInstance());
			$captcha->init();

			/* task */

			$labelElement = new Element('label', array(
				'for' => 'task',
				'class' => $this->_options['className']['captcha']['label']
			));
			$labelElement->text($captcha->getTask());
			$taskElement = new Element('input', array(
				'type' => 'number',
				'id' => 'task',
				'name' => 'task',
				'class' => $this->_options['className']['captcha']['field'],
				'min' => 1,
				'max' => 2,
				'required' => 'required'
			));
			$formElement->append('<li>' . $labelElement . $taskElement . '</li>');
		}
		$formElement->wrap('ul');

		/* captcha solution */

		if ($this->_options['captcha'])
		{
			$solutionElement = new Element('input', array(
				'type' => 'hidden',
				'name' => 'solution',
				'value' => $captcha->getSolution()
			));
			$formElement->append($solutionElement);
		}

		/* token */

		if ($token && $this->_options['token'])
		{
			$tokenElement = new Element('input', array(
				'type' => 'hidden',
				'name' => 'token',
				'value' => $token
			));
			$formElement->append($tokenElement);
		}

		/* collect output */

		$output .= $formElement;
		$output .= Hook::trigger('form_end');
		return $output;
	}

	/**
	 * process post and get
	 *
	 * @since 2.6.0
	 *
	 * @param Request $request instance of the request class
	 *
	 * @return string
	 */

	public function process(Request $request)
	{
		$post = $request->getPost();
		$query = $request->getQuery();

		/* handle post and get */

		if ($post)
		{
			return $post;
		}
		else
		{
			return $query;
		}
	}
}
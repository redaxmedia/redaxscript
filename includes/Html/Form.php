<?php
namespace Redaxscript\Html;

use Redaxscript\Hook;
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
	 * options of the form
	 *
	 * @var array
	 */

	protected $_options = array(
		'className' => array(
			'form' => 'js-validate-form form-default'
		),
		'method' => 'post',
		'action' => ''
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.6.0
	 *
	 * @param Registry $registry instance of the registry class
	 */

	public function __construct(Registry $registry)
	{
		$this->_registry = $registry;
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
		if ($token)
		{
			$tokenElement = new Element('input', array(
				'type' => 'hidden',
				'name' => 'token',
				'value' => $token
			));
		}

		/* collect output */

		$output .= $formElement->html($this->_html)->append($tokenElement);
		$output .= Hook::trigger('form_end');
		return $output;
	}
}
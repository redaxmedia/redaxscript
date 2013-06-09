<?php

/**
 * Redaxscript Captcha
 *
 * @since 2.0
 *
 * @category Captcha
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Captcha
{
	/**
	 * task
	 * @var string
	 */

	private $_task;

	/**
	 * solution
	 * @var string
	 */

	private $_solution;

	/**
	 * range
	 * @var array
	 */

	protected $_range = array(
		'min' => 1,
		'max' => 10
	);

	/**
	 * operators
	 * @var array
	 */

	protected $_operators = array(
		1 => 'plus',
		-1 => 'minus'
	);

	/**
	 * construct
	 *
	 * @since 2.0
	 */

	public function __construct()
	{
		/* call init */

		$this->init();
	}

	/**
	 * init
	 *
	 * @since 2.0
	 */

	public function init()
	{
		$this->_createCaptcha();
	}

	/**
	 * getTask
	 *
	 * @since 2.0
	 *
	 * @return $_task string
	 */

	public function getTask()
	{
		return $this->_task;
	}

	/**
	 * getSolution
	 *
	 * @since 2.0
	 *
	 * @return $_solution number
	 */

	public function getSolution()
	{
		return $this->_solution;
	}

	/**
	 * checkOperator
	 *
	 * @since 2.0
	 *
	 * @return $output number
	 */

	protected function _checkOperator()
	{
		/* switch captcha mode */

		switch (s('captcha'))
		{
			case 2:
				$output = 1;
				break;
			case 3:
				$output = -1;
				break;
			default:
				$output = mt_rand(0, 1) * 2 - 1;
				break;
		}
		return $output;
	}

	/**
	 * createCaptcha
	 *
	 * @since 2.0
	 */

	protected function _createCaptcha()
	{
		/* range */

		$min = $this->_range['min'];
		$max = $this->_range['max'];

		/* random numbers */

		$a = mt_rand($min + 1, $max);
		$b = mt_rand($min, $a - 1);

		/* operator */

		$c = $this->_checkOperator();
		$operator = $this->_operators[$c];

		/* solution and task */

		$this->_solution = sha1($a + $b * $c);
		$this->_task = l($a) . ' ' . l($operator) . ' ' . l($b);
	}
}
?>
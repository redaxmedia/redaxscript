<?php

/**
 * Redaxscript Captcha
 *
 * @since 1.3
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
	 * @var number
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
	 * @since 1.3
	 *
	 */

	public function __construct()
	{
		/* call init */

		$this->init();
	}

	/**
	 * init
	 *
	 * @since 1.3
	 */

	public function init()
	{
		$this->_createCaptcha();
	}

	/**
	 * get task
	 *
	 * @since 1.3
	 *
	 * @return $_task string
	 */

	public function getTask()
	{
		return $this->_task;
	}

	/**
	 * get solution
	 *
	 * @since 1.3
	 *
	 * @return $_solution number
	 */

	public function getSolution()
	{
		return $this->_solution;
	}

	/**
	 * create operator
	 *
	 * @since 1.3
	 *
	 * @return $output number
	 */

	protected function _createOperator()
	{
		switch (s('captcha'))
		{
			case 1:
				$output = mt_rand(0, 1) * 2 - 1;
				break;
			case 2:
				$output = 1;
				break;
			case 3:
				$output = -1;
				break;
		}
		return $output;
	}

	/**
	 * create captcha
	 *
	 * @since 1.3
	 *
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

		$c = $this->_createOperator();
		$d = $this->_operators[$c];

		/* solution and task */

		$this->_solution = sha1($a + $b * $c);
		$this->_task = l($a) . ' ' . l($d) . ' ' . l($b);
	}
}
?>
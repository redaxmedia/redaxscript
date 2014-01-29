<?php

/**
 * Redaxscript Captcha
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Captcha
 * @author Henry Ruhs
 */

class Redaxscript_Captcha
{
	/**
	 * task
	 *
	 * @var string
	 */

	private $_task;

	/**
	 * solution
	 *
	 * @var string
	 */

	private $_solution;

	/**
	 * range
	 *
	 * @var array
	 */

	protected $_range = array(
		'min' => 1,
		'max' => 10
	);

	/**
	 * operators
	 *
	 * @var array
	 */

	protected $_operators = array(
		1 => 'plus',
		-1 => 'minus'
	);

	/**
	 * construct
	 *
	 * @since 2.0.0
	 */

	public function __construct()
	{
		/* call init */

		$this->init();
	}

	/**
	 * init
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		$this->_createCaptcha();
	}

	/**
	 * getTask
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */

	public function getTask()
	{
		return $this->_task;
	}

	/**
	 * getSolution
	 *
	 * @since 2.0.0
	 *
	 * @param string $mode
	 * @return integer
	 */

	public function getSolution($mode = 'hash')
	{
		/* raw output */

		if ($mode === 'raw')
		{
			return $this->_solution;
		}

		/* else hash output */

		else
		{
			return sha1($this->_solution);
		}
	}

	/**
	 * checkOperator
	 *
	 * @since 2.0.0
	 *
	 * @return integer
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
	 * @since 2.0.0
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

		$this->_solution = $a + $b * $c;
		$this->_task = l($a) . ' ' . l($operator) . ' ' . l($b);
	}
}
?>
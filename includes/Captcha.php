<?php

/**
 * parent class to provide a simple mathematical task to ensure human users
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
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * task to be solved
	 *
	 * @var string
	 */

	private $_task;

	/**
	 * solution to the task
	 *
	 * @var string
	 */

	private $_solution;

	/**
	 * allowable range for the task
	 *
	 * @var array
	 */

	protected $_range = array(
		'min' => 1,
		'max' => 10
	);

	/**
	 * array of mathematical operators used for the task
	 *
	 * @var array
	 */

	protected $_operators = array(
		1 => 'plus',
		-1 => 'minus'
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.0.0
	 *
	 * @param Redaxscript_Language $language instance of the language class
	 */

	public function __construct(Redaxscript_Language $language)
	{
		$this->_language = $language;
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		$this->_create();
	}

	/**
	 * get the current task
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
	 * get the solution to the current task
	 *
	 * @since 2.0.0
	 *
	 * @param string $mode switch between plain text and hash solution
	 *
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
	 * get the mathematical operator used for the task
	 *
	 * @since 2.0.0
	 *
	 * @return integer
	 */

	protected function _getOperator()
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
	 * create a task of two numbers between allowable range
	 *
	 * @since 2.0.0
	 */

	protected function _create()
	{
		/* range */

		$min = $this->_range['min'];
		$max = $this->_range['max'];

		/* random numbers */

		$a = mt_rand($min + 1, $max);
		$b = mt_rand($min, $a - 1);

		/* operator */

		$c = $this->_getOperator();
		$operator = $this->_operators[$c];

		/* solution and task */

		$this->_solution = $a + $b * $c;
		$this->_task = $this->_language->get($a, '_number') . ' ' . $this->_language->get($operator) . ' ' . $this->_language->get($b, '_number');
	}
}
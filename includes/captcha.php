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
		$this->_buildCaptcha();
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
	 * build captcha
	 *
	 * @since 1.3
	 *
	 */

	protected function _buildCaptcha()
	{
		/* operator attay */

		$operator = array(
			1 => l('plus'),
			-1 => l('minus')
		);

		/* random numbers */

		$a = mt_rand(2, 10);
		$b = mt_rand(1, $a - 1);

		/* switch captcha mode */

		switch (s('captcha'))
		{
			case 1:
				$c = mt_rand(0, 1) * 2 - 1;
				break;
			case 2:
				$c = 1;
				break;
			case 3:
				$c = -1;
				break;
		}

		/* solution and task */

		$this->_solution = sha1($a + $b * $c);
		$this->_task = l($a) . ' ' . $operator[$c] . ' ' . l($b);
	}
}
?>
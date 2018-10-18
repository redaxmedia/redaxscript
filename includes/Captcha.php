<?php
namespace Redaxscript;

/**
 * parent class to provide a mathematical task
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Captcha
 * @author Henry Ruhs
 */

class Captcha
{
	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * captcha operator mode
	 *
	 * @var int
	 */

	protected $_mode;

	/**
	 * task to be solved
	 *
	 * @var string
	 */

	protected $_task;

	/**
	 * solution to the task
	 *
	 * @var int
	 */

	protected $_solution;

	/**
	 * allowed range for the task
	 *
	 * @var array
	 */

	protected $_rangeArray =
	[
		'min' => 1,
		'max' => 10
	];

	/**
	 * array of mathematical operators used for the task
	 *
	 * @var array
	 */

	protected $_operatorArray =
	[
		1 => 'plus',
		-1 => 'minus'
	];

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Language $language instance of the language class
	 */

	public function __construct(Language $language)
	{
		$this->_language = $language;
	}

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param int $mode captcha operator mode
	 */

	public function init(int $mode = null)
	{
		if (is_numeric($mode))
		{
			$this->_mode = $mode;
		}
		else
		{
			$settingModel = new Model\Setting();
			$this->_mode = $settingModel->get('captcha');
		}
		$this->_create();
	}

	/**
	 * get the task
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */

	public function getTask() : string
	{
		return $this->_task;
	}

	/**
	 * get the solution
	 *
	 * @since 2.6.0
	 *
	 * @return int
	 */

	public function getSolution() : int
	{
		return $this->_solution;
	}

	/**
	 * get the minimum range
	 *
	 * @since 2.6.0
	 *
	 * @return int
	 */

	public function getMin() : int
	{
		return $this->_rangeArray['min'];
	}

	/**
	 * get the maximum range
	 *
	 * @since 2.6.0
	 *
	 * @return int
	 */

	public function getMax() : int
	{
		return $this->_rangeArray['max'];
	}

	/**
	 * get the mathematical operator used for the task
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */

	protected function _getOperator() : int
	{
		if ($this->_mode === 2)
		{
			return 1;
		}
		if ($this->_mode === 3)
		{
			return -1;
		}
		return mt_rand(0, 1) * 2 - 1;
	}

	/**
	 * create a task of two numbers between allowable range
	 *
	 * @since 2.0.0
	 */

	protected function _create()
	{
		/* range */

		$min = $this->getMin();
		$max = $this->getMax();

		/* random numbers */

		$a = mt_rand($min + 1, $max);
		$b = mt_rand($min, $a - 1);

		/* operator */

		$c = $this->_getOperator();
		$operator = $this->_operatorArray[$c];

		/* solution and task */

		$this->_solution = $a + $b * $c;
		$this->_task = $this->_language->get($a, '_number') . ' ' . $this->_language->get($operator) . ' ' . $this->_language->get($b, '_number');
	}
}

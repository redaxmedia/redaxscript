<?php
namespace Redaxscript;

/**
 * parent class to provide a mathematical task to ensure human users
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
	 * @var object
	 */

	protected $_language;

	/**
	 * captcha operator mode
	 *
	 * @var integer
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
	 * @var integer
	 */

	protected $_solution;

	/**
	 * allowed range for the task
	 *
	 * @var array
	 */

	protected $_rangeArray = array(
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
	 * @param integer $mode captcha operator mode
	 */

	public function init($mode = null)
	{
		if (is_numeric($mode))
		{
			$this->_mode = $mode;
		}
		else
		{
			$this->_mode = Db::getSetting('captcha');
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

	public function getTask()
	{
		return $this->_task;
	}

	/**
	 * get the solution
	 *
	 * @since 2.6.0
	 *
	 * @return integer
	 */

	public function getSolution()
	{
		return $this->_solution;
	}

	/**
	 * get the minimum range
	 *
	 * @since 2.6.0
	 *
	 * @return integer
	 */

	public function getMin()
	{
		return $this->_rangeArray['min'];
	}

	/**
	 * get the maximum range
	 *
	 * @since 2.6.0
	 *
	 * @return integer
	 */

	public function getMax()
	{
		return $this->_rangeArray['max'];
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
		/* switch mode */

		switch ($this->_mode)
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

		$min = $this->getMin();
		$max = $this->getMax();

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
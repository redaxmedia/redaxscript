<?php

/**
 * Redaxscript_Detection
 *
 * @since 1.3
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Detection
{
	/**
	 * output
	 * @var string
	 */

	private $_output;

	/**
	 * construct
	 *
	 * @since 1.3
	 */

	public function __construct()
	{
		$this->init();
	}

	/**
	 * getOutput
	 *
	 * @since 1.3
	 *
	 * @return $_output string
	 */

	public function getOutput()
	{
		return $this->_output;
	}

	/**
	 * getParameter
	 *
	 * @since 1.3
	 *
	 * @return $output string
	 */

	protected function _getParameter($parameter = '')
	{
		if ($_GET[$parameter])
		{
			/* clean parameter */

			$output = clean($_GET[$parameter], 1);
			return $output;
		}
	}

	/**
	 * detection
	 *
	 * @since 1.3
	 *
	 * @param $input array
	 * @param $type string
	 * @param $path string
	 */

	protected function _detection($input = '', $type = '', $path = '')
	{
		foreach ($input as $key => $value)
		{
			if ($value)
			{
				$path = str_replace('{type}', $value, $path);

				/* if file exists */

				if (file_exists($path))
				{
					$this->_output = $value;

					/* save parameter in session */

					if ($key === 'parameter')
					{
						$_SESSION[ROOT . '/' . $type] = $value;
					}
					break;
				}
			}
		}
	}
}

/**
 * Redaxscript_Detection_Language
 *
 * @since 1.3
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Detection_Language extends Redaxscript_Detection
{
	/**
	 * init
	 *
	 * @since 1.3
	 */

	public function init()
	{
		$this->_detection(array(
			'parameter' => $this->_getParameter('l'),
			'session' => $_SESSION[ROOT . '/language'],
			'contents' => retrieve('language', LAST_TABLE, 'id', LAST_ID),
			'settings' => s('language') === 'detect' ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : s('language'),
			'fallback' => 'en'
		), 'language', 'languages/{type}.php');
	}
}

/**
 * Redaxscript_Detection_Template
 *
 * @since 1.3
 *
 * @category Detection
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Detection_Template extends Redaxscript_Detection
{
	/**
	 * init
	 *
	 * @since 1.3
	 */

	public function init()
	{
		$this->_detection(array(
			'parameter' => $this->_getParameter('t'),
			'session' => $_SESSION[ROOT . '/template'],
			'contents' => retrieve('template', LAST_TABLE, 'id', LAST_ID),
			'settings' => s('template'),
			'fallback' => 'default'
		), 'template', 'templates/{type}/index.phtml');
	}
}
?>
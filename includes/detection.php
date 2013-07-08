<?php

/**
 * Redaxscript Detection
 *
 * @since 2.0
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
	 * @since 2.0
	 */

	public function __construct()
	{
		$this->init();
	}

	/**
	 * getOutput
	 *
	 * @since 2.0
	 *
	 * @return string
	 */

	public function getOutput()
	{
		return $this->_output;
	}

	/**
	 * getParameter
	 *
	 * @since 2.0
	 *
	 * @return string
	 */

	protected function _getParameter($parameter = '')
	{
		if (isset($_GET[$parameter]))
		{
			/* clean parameter */

			$output = clean($_GET[$parameter], 1);
			return $output;
		}
	}

	/**
	 * detect
	 *
	 * @since 2.0
	 *
	 * @param array $input
	 * @param string $type
	 * @param string $route
	 */

	protected function _detect($input = '', $type = '', $route = '')
	{
		foreach ($input as $key => $value)
		{
			if ($value)
			{
				$file = str_replace('{type}', $value, $route);

				/* if file exists */

				if (file_exists($file))
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
 * @since 2.0
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
	 * @since 2.0
	 */

	public function init()
	{
		$this->_detect(array(
			'parameter' => $this->_getParameter('l'),
			'session' => isset($_SESSION[ROOT . '/language']) ? $_SESSION[ROOT . '/language'] : '',
			'contents' => retrieve('language', LAST_TABLE, 'id', LAST_ID),
			'settings' => s('language') === 'detect' ? '' : s('language'),
			'browser' => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : '',
			'fallback' => 'en'
		), 'language', 'languages/{type}.php');
	}
}

/**
 * Redaxscript_Detection_Template
 *
 * @since 2.0
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
	 * @since 2.0
	 */

	public function init()
	{
		$this->_detect(array(
			'parameter' => $this->_getParameter('t'),
			'session' => isset($_SESSION[ROOT . '/template']) ? $_SESSION[ROOT . '/template'] : '',
			'contents' => retrieve('template', LAST_TABLE, 'id', LAST_ID),
			'settings' => s('template'),
			'fallback' => 'default'
		), 'template', 'templates/{type}/index.phtml');
	}
}
?>
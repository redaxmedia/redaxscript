<?php

/**
 * Redaxscript Detection
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

	protected $_output;

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
	 * init
	 *
	 * @since 1.3
	 */

	public function init()
	{
		$this->detection();
	}

	/**
	 * get output
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
	 * get parameter
	 *
	 * @since 1.3
	 *
	 * @return $_output string
	 */

	protected function getParameter($parameter = '')
	{
		if ($_GET[$parameter])
		{
			/* clean get parameter */

			$output = clean($_GET[$parameter], 1);
			return $output;
		}
	}
}

/**
 * Redaxscript Detection Language
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
	 * detection
	 *
	 * @since 1.3
	 */

	public function detection()
	{
		$languageArray = array(
			'parameter' => $this->getParameter('l'),
			'session' => $_SESSION[ROOT . '/language'],
			'settings' => s('language'),
			'browser' => substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2),
			'fallback' => 'en'
		);

		/* check language file */

		foreach ($languageArray as $key => $language)
		{
			if (file_exists('languages/' . $language . '.php'))
			{
				$this->_output = $language;

				/* if parameter store in session */

				if ($key === 'parameter')
				{
					$_SESSION[ROOT . '/language'] = $language;
				}
				break;
			}
		}
	}
}

/**
 * Redaxscript Detection Template
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
	 * detection
	 *
	 * @since 1.3
	 */

	public function detection()
	{
		$templateArray = array(
			'parameter' => $this->getParameter('t'),
			'session' => $_SESSION[ROOT . '/template'],
			'content' => retrieve('template', LAST_TABLE, 'id', LAST_ID),
			'settings' => s('template'),
			'fallback' => 'default'
		);

		/* check template file */

		foreach ($templateArray as $key => $template)
		{
			if (file_exists('templates/' . $template . '/index.phtml'))
			{
				$this->_output = $template;

				/* if parameter store in session */

				if ($key === 'parameter')
				{
					$_SESSION[ROOT . '/template'] = $template;
				}
				break;
			}
		}
	}
}
?>
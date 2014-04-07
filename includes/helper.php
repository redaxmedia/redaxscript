<?php

/**
 * The Helper class provides information required to correctly render a page
 *
 * Provides character subset, text direction, browser class, device class and content type
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Helper
 * @author Henry Ruhs
 * @author Kim Kha Nguyen
 */

class Redaxscript_Helper
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * default character subset
	 *
	 * @var string
	 */

	protected $_subsetDefault = 'latin';

	/**
	 * array of languages requiring character subsets other than the default
	 *
	 * @var array
	 */

	protected $_subsetArray = array(
		'cyrillic' => array(
			'bg',
			'ru'
		),
		'vietnamese' => array(
			'vi'
		)
	);

	/**
	 * list of device types for responsive templates
	 *
	 * @var array
	 */

	protected $_deviceArray = array(
		'mobile' => 'myMobile',
		'tablet' => 'myTablet',
		'desktop' => 'myDesktop'
	);

	/**
	 * default text direction
	 *
	 * @var string
	 */

	protected $_directionDefault = 'ltr';

	/**
	 * array of languages requiring non-default text direction
	 *
	 * @var array
	 */

	protected $_directionArray = array(
		'rtl' => array(
			'ar',
			'fa',
			'he'
		)
	);

	/**
	 * constructor
	 *
	 * @since 2.1.0
	 * 
	 * @param Redaxscript_Registry $registry Instance of the registry
	 */

	public function __construct(Redaxscript_Registry $registry)
	{
		$this->_registry = $registry;
	}

	/**
	 * return the character subset required by the current language
	 *
	 * @since 2.1.0
	 * 
	 * @return string
	 */

	public function getSubset()
	{
		$output = $this->_subsetDefault;

		/* process subset */

		foreach ($this->_subsetArray as $key => $value)
		{
			if (in_array($this->_registry->get('language'), $value))
			{
				$output = $key;
			}
		}
		return $output;
	}

	/**
	 * return the current browser, device, content and text direction
	 *
	 * @since 2.1.0
	 * 
	 * @return string
	 */

	public function getClass()
	{
		/* merge classes */

		$classes = array_merge(
			$this->_getBrowserClass(),
			$this->_getDeviceClass(),
			$this->_getContentClass(),
			$this->_getDirectionClass()
		);

		/* implode classes */

		$output = implode(' ', array_unique($classes));
		return $output;
	}

	/**
	 * return browser name and version, and browser engine
	 *
	 * @since 2.1.0
	 * 
	 * @return array
	 */

	protected function _getBrowserClass()
	{
		$output = array();

		/* browser and version */

		$output[] = $this->_registry->get('myBrowser') . $this->_registry->get('myBrowserVersion');

		/* engine */

		if ($this->_registry->get('myEngine'))
		{
			$output[] = $this->_registry->get('myEngine');
		}
		return $output;
	}

	/**
	 * return device class
	 *
	 * @since 2.1.0
	 * 
	 * @return array
	 */

	protected function _getDeviceClass()
	{
		$output = array();

		/* process device */

		foreach ($this->_deviceArray as $key => $value)
		{
			if ($this->_registry->get($value))
			{
				$output[] = $key;
				if ($this->_registry->get($value) !== $key)
				{
					$output[] = $this->_registry->get($value);
				}
			}
		}
		return $output;
	}

	/**
	 * return content class (category or article)
	 *
	 * @since 2.1.0
	 * 
	 * @return array
	 */

	protected function _getContentClass()
	{
		$output = array();

		/* category */

		if ($this->_registry->get('category'))
		{
			$output[] = 'category';
		}

		/* article */

		else if ($this->_registry->get('article'))
		{
			$output[] = 'article';
		}
		return $output;
	}

	/**
	 * return text direction required by current language
	 *
	 * @since 2.1.0
	 * 
	 * @return array
	 */

	protected function _getDirectionClass()
	{
		$output[0] = $this->_directionDefault;

		/* process direction */

		foreach ($this->_directionArray as $key => $value)
		{
			if (in_array($this->_registry->get('language'), $value))
			{
				$output[0] = $key;
			}
		}
		return $output;
	}
}
?>
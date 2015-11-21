<?php
namespace Redaxscript;

/**
 * parent class to provide required helper for templates
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Helper
 * @author Henry Ruhs
 * @author Kim Kha Nguyen
 */

class Helper
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
	 * array of languages and related character subset
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
	 * array of device types
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
	 * array of languages and related text direction
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
	 * constructor of the class
	 *
	 * @since 2.1.0
	 *
	 * @param Registry $registry instance of the registry class
	 */

	public function __construct(Registry $registry)
	{
		$this->_registry = $registry;
	}

	/**
	 * get the helper subset
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
	 * get the helper class
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */

	public function getClass()
	{
		/* merge classes */

		$classArray = array_merge(
			$this->_getBrowserArray(),
			$this->_getDeviceArray(),
			$this->_getContentArray(),
			$this->_getDirectionArray()
		);

		/* implode classes */

		$output = implode(' ', array_unique($classArray));
		return $output;
	}

	/**
	 * get the browser array
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	protected function _getBrowserArray()
	{
		$output = array();

		/* browser and version */

		if ($this->_registry->get('myBrowser') && $this->_registry->get('myBrowserVersion'))
		{
			$output[] = 'rs-' . $this->_registry->get('myBrowser') . ' rs-version-' . $this->_registry->get('myBrowserVersion');
		}

		/* engine */

		if ($this->_registry->get('myEngine'))
		{
			$output[] = 'rs-' . $this->_registry->get('myEngine');
		}
		return $output;
	}

	/**
	 * get the device array
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	protected function _getDeviceArray()
	{
		$output = array();

		/* process device */

		foreach ($this->_deviceArray as $key => $value)
		{
			if ($this->_registry->get($value))
			{
				$output[] = 'rs-' . $key;
				if ($this->_registry->get($value) !== $key)
				{
					$output[] = 'rs-' . $this->_registry->get($value);
				}
			}
		}
		return $output;
	}

	/**
	 * get the content array
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	protected function _getContentArray()
	{
		$output = array();

		/* category */

		if ($this->_registry->get('category'))
		{
			$output[] = 'rs-category';
		}

		/* article */

		else if ($this->_registry->get('article'))
		{
			$output[] = 'rs-article';
		}
		return $output;
	}

	/**
	 * get the direction array
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	protected function _getDirectionArray()
	{
		$output = array();
		$output[0] = 'rs-' . $this->_directionDefault;

		/* process direction */

		foreach ($this->_directionArray as $key => $value)
		{
			if (in_array($this->_registry->get('language'), $value))
			{
				$output[0] = 'rs-' . $key;
			}
		}
		return $output;
	}
}
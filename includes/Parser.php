<?php
namespace Redaxscript;

use Redaxscript\Html;

/**
 * parent class to parse content for pseudo tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Parser
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * output of the parser
	 *
	 * @var string
	 */

	protected $_output;

	/**
	 * array of pseudo tags
	 *
	 * @var array
	 */

	protected $_tagArray = array(
		'readmore' => array(
			'method' => '_parseReadmore',
			'position' => null,
			'search' => array(
				'<readmore>'
			)
		),
		'codequote' => array(
			'method' => '_parseCodequote',
			'position' => null,
			'search' => array(
				'<codequote>',
				'</codequote>'
			)
		),
		'language' => array(
			'method' => '_parseLanguage',
			'position' => null,
			'search' => array(
				'<language>',
				'</language>'
			)
		),
		'registry' => array(
			'method' => '_parseRegistry',
			'position' => null,
			'search' => array(
				'<registry>',
				'</registry>'
			)
		),
		'template' => array(
			'method' => '_parseTemplate',
			'namespace' => 'Redaxscript\Template\Tag',
			'position' => null,
			'search' => array(
				'<template>',
				'</template>'
			)
		),
		'module' => array(
			'method' => '_parseModule',
			'namespace' => 'Redaxscript\Modules\\',
			'position' => null,
			'search' => array(
				'<module>',
				'</module>'
			)
		)
	);

	/**
	 * options of the parser
	 *
	 * @var array
	 */

	protected $_optionArray = array(
		'className' => array(
			'readmore' => 'rs-link-readmore',
			'codequote' => 'rs-js-code-quote rs-code-quote'
		),
		'delimiter' => '@@@'
	);

	/**
	 * constructor of the class
	 *
	 * @since 2.4.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * init the class
	 *
	 * @since 2.4.0
	 *
	 * @param string $content content to be parsed
	 * @param array $optionArray options of the parser
	 */

	public function init($content = null, $optionArray = null)
	{
		$this->_output = $content;
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}

		/* process tags */

		foreach ($this->_tagArray as $key => $value)
		{
			 $this->_tagArray[$key]['position'] = strpos($this->_output, '<' . $key . '>');

			/* call related method */

			if ($this->_tagArray[$key]['position'] > -1)
			{
				$method = $value['method'];
				$this->_output = $this->$method($this->_output);
			}
		}
	}

	/**
	 * get the output
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */

	public function getOutput()
	{
		return $this->_output;
	}

	/**
	 * parse the readmore tag
	 *
	 * @since 2.6.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	protected function _parseReadmore($content = null)
	{
		$output = str_replace($this->_tagArray['readmore']['search'], '', $content);

		/* html elements */

		$linkElement = new Html\Element();
		$linkElement->init('a', array(
			'class' => $this->_optionArray['className']['readmore'],
			'title' => $this->_language->get('readmore')
		));

		/* collect output */

		if ($this->_registry->get('lastTable') === 'categories' || !$this->_registry->get('fullRoute'))
		{
			$output = substr($output, 0, $this->_tagArray['readmore']['position']);
			if ($this->_optionArray['route'])
			{
				$output .= $linkElement
					->copy()
					->attr('href', $this->_registry->get('parameterRoute') . $this->_optionArray['route'])
					->text($this->_language->get('readmore'));
			}
		}
		return $output;
	}

	/**
	 * parse the codequote tag
	 *
	 * @since 2.6.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	protected function _parseCodequote($content = null)
	{
		$output = str_replace($this->_tagArray['codequote']['search'], $this->_optionArray['delimiter'], $content);
		$parts = array_filter(explode($this->_optionArray['delimiter'], $output));

		/* html elements */

		$preElement = new Html\Element();
		$preElement->init('pre', array(
			'class' => $this->_optionArray['className']['codequote']
		));

		/* parse as needed */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				$parts[$key] = $preElement->copy()->html(htmlspecialchars($value));
			}
		}
		$output = implode($parts);
		return $output;
	}

	/**
	 * parse the language tag
	 *
	 * @since 2.5.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	protected function _parseLanguage($content = null)
	{
		$output = str_replace($this->_tagArray['language']['search'], $this->_optionArray['delimiter'], $content);
		$parts = array_filter(explode($this->_optionArray['delimiter'], $output));

		/* parse as needed */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				$parts[$key] = $this->_language->get($value);
			}
		}
		$output = implode($parts);
		return $output;
	}

	/**
	 * parse the registry tag
	 *
	 * @since 2.5.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	protected function _parseRegistry($content = null)
	{
		$output = str_replace($this->_tagArray['registry']['search'], $this->_optionArray['delimiter'], $content);
		$parts = array_filter(explode($this->_optionArray['delimiter'], $output));

		/* parse as needed */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				$parts[$key] = $this->_registry->get($value);
			}
		}
		$output = implode($parts);
		return $output;
	}

	/**
	 * parse the template tag
	 *
	 * @since 3.0.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	protected function _parseTemplate($content = null)
	{
		$output = str_replace($this->_tagArray['template']['search'], $this->_optionArray['delimiter'], $content);
		$parts = array_filter(explode($this->_optionArray['delimiter'], $output));
		$object = $this->_tagArray['template']['namespace'];

		/* parse as needed */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				$parts[$key] = null;
				$json = json_decode($value, true);

				/* call with parameter */

				if (is_array($json))
				{
					foreach ($json as $method => $parameterArray)
					{
						/* method exists */

						if (method_exists($object, $method))
						{
							$parts[$key] = call_user_func_array(array(
								$object,
								$method
							), $parameterArray);
						}
					}
				}

				/* else simple call */

				else if (method_exists($object, $value))
				{
					$parts[$key] = call_user_func(array(
						$object,
						$value
					));
				}
			}
		}
		$output = implode($parts);
		return $output;
	}

	/**
	 * parse the module tag
	 *
	 * @since 3.0.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	protected function _parseModule($content = null)
	{
		$output = str_replace($this->_tagArray['module']['search'], $this->_optionArray['delimiter'], $content);
		$parts = array_filter(explode($this->_optionArray['delimiter'], $output));
		$modulesLoaded = Hook::getModuleArray();

		/* parse as needed */

		foreach ($parts as $key => $value)
		{
			$object = $this->_tagArray['module']['namespace'] . $value . '\\' . $value;
			if ($key % 2)
			{
				$parts[$key] = null;
				$json = json_decode($value, true);

				/* call with parameter */

				if (is_array($json))
				{
					foreach ($json as $module => $parameterArray)
					{
						$object = $this->_tagArray['module']['namespace'] . $module . '\\' . $module;

						/* method exists */

						if (in_array($module, $modulesLoaded) && method_exists($object, 'render'))
						{
							$parts[$key] = call_user_func_array(array(
								$object,
								'render'
							), $parameterArray);
						}
					}
				}

				/* else simple call */

				else if (in_array($value, $modulesLoaded) && method_exists($object, 'render'))
				{
					$parts[$key] = call_user_func(array(
						$object,
						'render'
					));
				}
			}
		}
		$output = implode($parts);
		return $output;
	}
}
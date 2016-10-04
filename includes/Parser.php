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

	protected $_tagArray =
	[
		'readmore' =>
		[
			'method' => '_parseReadmore',
			'position' => null,
			'search' =>
			[
				'<readmore>'
			]
		],
		'codequote' =>
		[
			'method' => '_parseCodequote',
			'position' => null,
			'search' =>
			[
				'<codequote>',
				'</codequote>'
			]
		],
		'language' =>
		[
			'method' => '_parseLanguage',
			'position' => null,
			'search' =>
			[
				'<language>',
				'</language>'
			]
		],
		'registry' =>
		[
			'method' => '_parseRegistry',
			'position' => null,
			'search' =>
			[
				'<registry>',
				'</registry>'
			]
		],
		'template' =>
		[
			'method' => '_parseTemplate',
			'namespace' => 'Redaxscript\Template\Tag',
			'position' => null,
			'search' =>
			[
				'<template>',
				'</template>'
			]
		],
		'module' =>
		[
			'method' => '_parseModule',
			'namespace' => 'Redaxscript\Modules\\',
			'position' => null,
			'search' =>
			[
				'<module>',
				'</module>'
			]
		]
	];

	/**
	 * options of the parser
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'readmore' => 'rs-link-readmore',
			'codequote' => 'rs-js-codequote rs-box-codequote'
		],
		'delimiter' => '@@@'
	];

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
		$linkElement->init('a',
		[
			'class' => $this->_optionArray['className']['readmore']
		]);

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
		$partArray = array_filter(explode($this->_optionArray['delimiter'], $output));

		/* html elements */

		$preElement = new Html\Element();
		$preElement->init('pre',
		[
			'class' => $this->_optionArray['className']['codequote']
		]);

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			if ($key % 2)
			{
				$partArray[$key] = $preElement->copy()->html(htmlspecialchars($value));
			}
		}
		$output = implode($partArray);
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
		$partArray = array_filter(explode($this->_optionArray['delimiter'], $output));

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			if ($key % 2)
			{
				$partArray[$key] = $this->_language->get($value);
			}
		}
		$output = implode($partArray);
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
		$partArray = array_filter(explode($this->_optionArray['delimiter'], $output));

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			if ($key % 2)
			{
				$partArray[$key] = $this->_registry->get($value);
			}
		}
		$output = implode($partArray);
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
		$partArray = array_filter(explode($this->_optionArray['delimiter'], $output));
		$object = $this->_tagArray['template']['namespace'];

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			if ($key % 2)
			{
				$partArray[$key] = null;
				$json = json_decode($value, true);

				/* call with parameter */

				if (is_array($json))
				{
					foreach ($json as $method => $parameterArray)
					{
						/* method exists */

						if (method_exists($object, $method))
						{
							$partArray[$key] = call_user_func_array(
							[
								$object,
								$method
							], $parameterArray);
						}
					}
				}

				/* else simple call */

				else if (method_exists($object, $value))
				{
					$partArray[$key] = call_user_func(
					[
						$object,
						$value
					]);
				}
			}
		}
		$output = implode($partArray);
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
		$partArray = array_filter(explode($this->_optionArray['delimiter'], $output));
		$modulesLoaded = Hook::getModuleArray();

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			$object = $this->_tagArray['module']['namespace'] . $value . '\\' . $value;
			if ($key % 2)
			{
				$partArray[$key] = null;
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
							$partArray[$key] = call_user_func_array(
							[
								$object,
								'render'
							], $parameterArray);
						}
					}
				}

				/* else simple call */

				else if (in_array($value, $modulesLoaded) && method_exists($object, 'render'))
				{
					$partArray[$key] = call_user_func(
					[
						$object,
						'render'
					]);
				}
			}
		}
		$output = implode($partArray);
		return $output;
	}
}
<?php
namespace Redaxscript\Content\Tag;

/**
 * children class to parse content for template tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

class Template extends TagAbstract
{
	/**
	 * options of the template tag
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'search' =>
		[
			'<template>',
			'</template>'
		],
		'namespace' => 'Redaxscript\Template\Tag',
		'delimiter' => '@@@'
	];

	/**
	 * process the class
	 *
	 * @since 3.0.0
	 *
	 * @param string $content content to be parsed
	 *
	 * @return string
	 */

	public function process($content = null)
	{
		$output = str_replace($this->_optionArray['search'], $this->_optionArray['delimiter'], $content);
		$partArray = array_filter(explode($this->_optionArray['delimiter'], $output));

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
					foreach ($json as $methodName => $parameterArray)
					{
						$partArray[$key] = $this->_call($methodName, $parameterArray);
					}
				}

				/* else simple call */

				else
				{
					$partArray[$key] = $this->_call($value);
				}
			}
		}
		$output = implode($partArray);
		return $output;
	}

	/**
	 * call the method
	 *
	 * @since 3.2.0
	 *
	 * @param string $methodName
	 * @param array $parameterArray
	 *
	 * @return string|boolean
	 */

	protected function _call($methodName = null, $parameterArray = [])
	{
		$templateClass = $this->_optionArray['namespace'];
		if (method_exists($templateClass, $methodName))
		{
			$template = new $templateClass();
			return call_user_func_array(
			[
				$template,
				$methodName
			], $parameterArray);
		}
		return false;
	}
}
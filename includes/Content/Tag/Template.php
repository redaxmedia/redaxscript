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
		$templateClass = $this->_optionArray['namespace'];

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

						if (method_exists($templateClass, $method))
						{
							$partArray[$key] = call_user_func_array(
							[
								$templateClass,
								$method
							], $parameterArray);
						}
					}
				}

				/* else simple call */

				else if (method_exists($templateClass, $value))
				{
					$partArray[$key] = call_user_func(
					[
						$templateClass,
						$value
					]);
				}
			}
		}
		$output = implode($partArray);
		return $output;
	}
}
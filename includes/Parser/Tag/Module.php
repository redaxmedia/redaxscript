<?php
namespace Redaxscript\Parser\Tag;

use Redaxscript\Config;
use Redaxscript\Hook;
use Redaxscript\Request;

/**
 * children class to parse content for module tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Module extends TagAbstract
{
	/**
	 * options of the module tag
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'search' =>
		[
			'<module>',
			'</module>'
		],
		'namespace' => 'Redaxscript\Modules',
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
		$modulesLoaded = Hook::getModuleArray();

		/* parse as needed */

		foreach ($partArray as $key => $value)
		{
			$moduleClass = $this->_optionArray['namespace'] . '\\' . $value . '\\' . $value;
			if ($key % 2)
			{
				$partArray[$key] = null;
				$json = json_decode($value, true);

				/* call with parameter */

				if (is_array($json))
				{
					foreach ($json as $moduleName => $parameterArray)
					{
						$moduleClass = $this->_optionArray['namespace'] . '\\' . $moduleName . '\\' . $moduleName;

						/* method exists */

						if (in_array($moduleName, $modulesLoaded) && method_exists($moduleClass, 'render'))
						{
							$module = new $moduleClass($this->_registry, Request::getInstance(), $this->_language, Config::getInstance());
							$partArray[$key] = call_user_func_array(
							[
								$module,
								'render'
							], $parameterArray);
						}
					}
				}

				/* else simple call */

				else if (in_array($value, $modulesLoaded) && method_exists($moduleClass, 'render'))
				{
					$module = new $moduleClass($this->_registry, Request::getInstance(), $this->_language, Config::getInstance());
					$partArray[$key] = call_user_func(
					[
						$module,
						'render'
					]);
				}
			}
		}
		$output = implode($partArray);
		return $output;
	}
}
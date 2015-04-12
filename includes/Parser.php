<?php
namespace Redaxscript;

use Redaxscript\Validator;

/**
 * parent class to parse content for pseudo tags
 *
 * @since 2.0.0
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
	 * route of the content
	 *
	 * @var string
	 */

	protected $_route;

	/**
	 * options of the parser
	 *
	 * @var array
	 */

	protected $_options = array(
		'className' => array(
			'break' => 'link-read-more',
			'quote' => 'box-quote'
		)
	);

	/**
	 * delimiter used during the parser process
	 *
	 * @var string
	 */

	protected $_delimiter = '@@@';

	/**
	 * array of pseudo tags
	 *
	 * @var array
	 */

	protected $_tags = array(
		'break' => array(
			'method' => '_parseBreak',
			'position' => ''
		),
		'quote' => array(
			'method' => '_parseQuote',
			'position' => ''
		),
		'function' => array(
			'method' => '_parseFunction',
			'position' => ''
		),
		'module' => array(
			'method' => '_parseModule',
			'position' => ''
		)
	);

	/**
	 * array of functions that will not be executed
	 *
	 * @var array
	 */

	protected $_forbiddenFunctions = array(
		'curl',
		'curl_exec',
		'curl_multi_exec',
		'exec',
		'eval',
		'fopen',
		'include',
		'include_once',
		'mysql',
		'passthru',
		'popen',
		'proc_open',
		'shell',
		'shell_exec',
		'system',
		'require',
		'require_once'
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
	 * @param string $input content be parsed
	 * @param string $route route of the content
	 * @param array $options options of the parser
	 */

	public function init($input = null, $route = null, $options = null)
	{
		$this->_output = $input;
		$this->_route = $route;
		if (is_array($options))
		{
			$this->_options = array_unique(array_merge($this->_options, $options));
		}

		/* process tags */

		foreach ($this->_tags as $key => $value)
		{
			/* save tag position */

			 $this->_tags[$key]['position'] = strpos($this->_output, '<' . $key . '>');

			/* call related method */

			if ($this->_tags[$key]['position'] > -1)
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
	 * parse the break tag
	 *
	 * @since 2.0.0
	 *
	 * @param string $input content be parsed
	 *
	 * @return string
	 */

	protected function _parseBreak($input = null)
	{
		$aliasValidator = new Validator\Alias();
		$linkElement = new Element('a');

		/* collect output */

		$output = str_replace(array(
			'<break>',
			'</break>'
		), '', $input);
		if ($this->_registry->get('lastTable') === 'categories' || !$this->_registry->get('fullRoute') || $aliasValidator->validate($this->_registry->get('firstParameter'), Validator\Alias::MODE_DEFAULT) === Validator\ValidatorInterface::PASSED)
		{
			$output = substr($output, 0, $this->_tags['break']['position']);

			/* add read more */

			if ($this->_route)
			{
				$output .= $linkElement->attr(array(
					'href' => $this->_registry->get('rewriteRoute') . $this->_route,
					'class' => $this->_options['className']['break'],
					'title' => $this->_language->get('read_more')
				))->text($this->_language->get('read_more'));
			}
		}
		return $output;
	}

	/**
	 * parse the quote tag
	 *
	 * @since 2.0.0
	 *
	 * @param string $input content be parsed
	 *
	 * @return string
	 */

	protected function _parseQuote($input = null)
	{
		$output = str_replace(array(
			'<quote>',
			'</quote>'
		), $this->_delimiter, $input);
		$parts = array_filter(explode($this->_delimiter, $output));
		$preElement = new Element('pre', array(
			'class' => $this->_options['className']['code']
		));

		/* parse needed parts */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				$parts[$key] = $preElement->html(htmlspecialchars($value));
			}
		}
		$output = implode($parts);
		return $output;
	}

	/**
	 * parse the function tag pair
	 *
	 * @since 2.0.0
	 *
	 * @param string $input content be parsed
	 *
	 * @return string
	 */

	protected function _parseFunction($input = null)
	{
		$output = str_replace(array(
			'<function>',
			'</function>'
		), $this->_delimiter, $input);
		$parts = array_filter(explode($this->_delimiter, $output));

		/* parse needed parts */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				/* decode to array */

				$json = json_decode($value, true);
				ob_start();

				/* multiple calls with parameter */

				if (is_array($json))
				{
					foreach ($json as $function => $parameter)
					{
						if (function_exists($function) && !in_array($function, $this->_forbiddenFunctions))
						{
							echo call_user_func_array($function, $parameter);
						}
					}
				}

				/* else single call */

				else if (function_exists($value) && !in_array($value, $this->_forbiddenFunctions))
				{
					echo call_user_func($value);
				}
				$parts[$key] = ob_get_clean();
			}
		}
		$output = implode($parts);
		return $output;
	}

	/**
	 * parse the module tag
	 *
	 * @since 2.2.0
	 *
	 * @param string $input content be parsed
	 *
	 * @return string
	 */

	protected function _parseModule($input = null)
	{
		$namespace = 'Redaxscript\Modules\\';
		$output = str_replace(array(
			'<module>',
			'</module>'
		), $this->_delimiter, $input);
		$parts = array_filter(explode($this->_delimiter, $output));

		/* parse needed parts */

		foreach ($parts as $key => $value)
		{
			$object = $namespace . $value . '\\' . $value;
			if ($key % 2)
			{
				/* decode to array */

				$json = json_decode($value, true);
				ob_start();

				/* multiple calls with parameter */

				if (is_array($json))
				{
					foreach ($json as $module => $parameter)
					{
						$object = $namespace . $module . '\\' . $module;
						if (in_array($module, Hook::getModules()) && method_exists($object, 'render'))
						{
							echo call_user_func_array(array($object, 'render'), $parameter);
						}
					}
				}

				/* else single call */

				else if (in_array($value, Hook::getModules()) && method_exists($object, 'render'))
				{
					echo call_user_func(array($object, 'render'));
				}
				$parts[$key] = ob_get_clean();
			}
		}
		$output = implode($parts);
		return $output;
	}
}


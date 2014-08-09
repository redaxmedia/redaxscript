<?php
namespace Redaxscript;
use Redaxscript\Validator;
use Redaxscript_Validator_Interface as Redaxscript_Validator_Interface;

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
	 * parsed output
	 *
	 * @var string
	 */

	private $_output;

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
			'code' => 'box-code'
		)
	);

	/**
	 * string delimiter used during the parser process
	 *
	 * @var string
	 */

	protected $_delimiter = '@@@';

	/**
	 * array of pseudo tags and related functions
	 *
	 * @var array
	 */

	protected $_tags = array(
		'break' => array(
			'function' => '_parseBreak',
			'position' => ''
		),
		'code' => array(
			'function' => '_parseCode',
			'position' => ''
		),
		'function' => array(
			'function' => '_parseFunction',
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
	 * @since 2.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 * @param string $input content be parsed
	 * @param string $route route of the content
	 * @param array $options options of the parser
	 */

	public function __construct(Registry $registry, Language $language, $input = null, $route = null, $options = null)
	{
		$this->_registry = $registry;
		$this->_language = $language;
		$this->_output = $input;
		$this->_route = $route;
		if (is_array($options))
		{
			$this->_options = array_unique(array_merge($this->_options, $options));
		}
		$this->init();
	}

	/**
	 * init the class
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		foreach ($this->_tags as $key => $value)
		{
			/* save tag related position */

			 $this->_tags[$key]['position'] = strpos($this->_output, '<' . $key . '>');

			/* call related function if tag found */

			if ($this->_tags[$key]['position'] > -1)
			{
				$function = $value['function'];
				$this->_output = $this->$function($this->_output);
			}
		}
	}

	/**
	 * get the parsed output
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

		/* collect output */

		$output = str_replace('<break>', '', $input);
		if ($this->_registry->get('lastTable') === 'categories' || !$this->_registry->get('fullRoute') || $aliasValidator->validate($this->_registry->get('firstParameter'), Validator\Alias::ALIAS_MODE_DEFAULT) === Redaxscript_Validator_Interface::VALIDATION_OK)
		{
			$output = substr($output, 0, $this->_tags['break']['position']);

			/* add read more */

			if ($this->_route)
			{
				$output .= '<a href="' . $this->_registry->get('rewriteRoute') . $this->_route . '" class="' . $this->_options['className']['break'] . '" title="' . $this->_language->get('read_more') . '">' . $this->_language->get('read_more') . '</a>';
			}
		}
		return $output;
	}

	/**
	 * parse the code tag pair
	 *
	 * @since 2.0.0
	 *
	 * @param string $input content be parsed
	 *
	 * @return string
	 */

	protected function _parseCode($input = null)
	{
		$output = str_replace(array(
			'<code>',
			'</code>'
		), $this->_delimiter, $input);
		$parts = explode($this->_delimiter, $output);

		/* parse needed parts */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				$parts[$key] = '<code class="' . $this->_options['className']['code'] . '">' . trim(htmlspecialchars($value)) . '</code>';
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
		$parts = explode($this->_delimiter, $output);

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

				else
				{
					if (function_exists($value) && !in_array($value, $this->_forbiddenFunctions))
					{
						echo call_user_func($value);
					}
				}
				$parts[$key] = ob_get_clean();
			}
		}
		$output = implode($parts);
		return $output;
	}
}
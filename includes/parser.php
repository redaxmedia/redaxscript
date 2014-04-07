<?php

/**
 * The Parser class provides methods to parse articles for Redaxscript pseudo-tags
 *
 * @since 2.0.0
 *
 * @package Redaxscript
 * @category Parser
 * @author Henry Ruhs
 */

class Redaxscript_Parser
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * parsed article text
	 *
	 * @var string
	 */

	private $_output;

	/**
	 * URL of article used for read more... link
	 *
	 * @var string
	 */

	protected $_route;

	/**
	 * string used to replace code and function tags during parsing
	 *
	 * @var string
	 */

	protected $_delimiter = '@@@';

	/**
	 * array of Redaxscript pseudo-tags and functions to parse them with
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
	 * array of CSS classes to apply to pseudo-tags
	 *
	 * @var array
	 */

	protected $_classes = array(
		'break' => 'link_read_more',
		'code' => 'box_code'
	);

	/**
	 * list of forbidden functions which will not be executed
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
	 * constructor
	 *
	 * parses the article so init() does not normally have to be called
	 *
	 * @since 2.0.0
	 *
	 * @param Redaxscript_Registry $registry Instance of the registry class
	 * @param string $input Article text to be parsed
	 * @param string $route URL of the article
	 */

	public function __construct(Redaxscript_Registry $registry, $input = null, $route = null)
	{
		$this->_registry = $registry;
		$this->_output = $input;
		$this->_route = $route;

		/* call init */

		$this->init();
	}

	/**
	 * parse the article
	 *
	 * @since 2.0.0
	 */

	public function init()
	{
		foreach($this->_tags as $key => $value)
		{
			/* save tag related position */

			$position = $this->_tags[$key]['position'] = strpos($this->_output, '<' . $key . '>');

			/* call related function if tag found */

			if ($position > -1)
			{
				$function = $value['function'];
				$this->_output = $this->$function($this->_output);
			}
		}
	}

	/**
	 * return the modified article
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
	 * parse the <break> pseudo-tag
	 *
	 * @since 2.0.0
	 *
	 * @param string $input The article text to be parsed
	 * @return string
	 */

	protected function _parseBreak($input = null)
	{
		$output = str_replace('<break>', '', $input);
		if ($this->_registry->get('lastTable') === 'categories' || !$this->_registry->get('fullRoute') || check_alias($this->_registry->get('firstParameter'), 1) === 1)
		{
			$output = substr($output, 0, $this->_tags['break']['position']);

			/* add read more */

			if ($this->_route)
			{
				$output .= anchor_element('internal', '', $this->_classes['break'], l('read_more'), $this->_route);
			}
		}
		return $output;
	}

	/**
	 * parse the <code>...</code> pseudo-tag pair
	 *
	 * @since 2.0.0
	 *
	 * @param string $input The article text to be parsed
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
				$parts[$key] = '<code class="' . $this->_classes['code'] . '">' . trim(htmlspecialchars($value)) . '</code>';
			}
		}
		$output = implode($parts);
		return $output;
	}

	/**
	 * parse the <function>...</function> pseudo-tag pair
	 *
	 * @since 2.0.0
	 *
	 * @param string $input The article text to be parsed
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
				/* validate allowed functions */

				if (!in_array($value, $this->_forbiddenFunctions))
				{
					/* decode to array */

					$json = json_decode($value, true);
					ob_start();

					/* multiple calls with parameter */

					if (is_array($json))
					{
						foreach ($json as $function => $parameter)
						{
							echo call_user_func_array($function, $parameter);
						}
					}

					/* else single call */

					else
					{
						echo call_user_func($value);
					}
					$parts[$key] = ob_get_clean();
				}
			}
		}
		$output = implode($parts);
		return $output;
	}
}
?>
<?php

/**
 * parent class to parse content for pseudo tags
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
	 * array of classes used to style the output
	 *
	 * @var array
	 */

	protected $_classes = array(
		'break' => 'link_read_more',
		'code' => 'box_code'
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
	 * @param Redaxscript_Registry $registry instance of the registry class
	 * @param string $input content be parsed
	 * @param string $route route of the content
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
	 * init the class
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
				$parts[$key] = '<code class="' . $this->_classes['code'] . '">' . trim(htmlspecialchars($value)) . '</code>';
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
						if (!in_array($function, $this->_forbiddenFunctions))
						{
							echo call_user_func_array($function, $parameter);
						}
					}
				}

				/* else single call */

				else
				{
					if (!in_array($value, $this->_forbiddenFunctions))
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
?>

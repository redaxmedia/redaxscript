<?php

/**
 * Redaxscript Parser
 *
 * @since 1.3
 *
 * @category Parser
 * @package Redaxscript
 * @author Henry Ruhs
 */

class Redaxscript_Parser
{
	/**
	 * output
	 * @var string
	 */

	private $_output;

	/**
	 * route
	 * @var string
	 */

	protected $_route;

	/**
	 * tags
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
	 * classes
	 * @var array
	 */

	protected $_classes = array(
		'break' => 'link_read_more',
		'code' => 'box_code'
	);

	/**
	 * construct
	 *
	 * @since 1.3
	 *
	 * @param $input string
	 * @param $route string
	 */

	public function __construct($input = '', $route = '')
	{
		$this->_output = $input;
		$this->_route = $route;

		/* call init */

		$this->init();
	}

	/**
	 * init
	 *
	 * @since 1.3
	 */

	public function init()
	{
		foreach($this->_tags as $key => $value)
		{
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
	 * get output
	 *
	 * @since 1.3
	 *
	 * @return $_output string
	 */

	public function getOutput()
	{
		return $this->_output;
	}

	/**
	 * parse break
	 *
	 * @since 1.3
	 *
	 * @param $input string
	 * @return $output string
	 */

	protected function _parseBreak($input = '')
	{
		$output = str_replace('<break>', '', $input);
		if (LAST_TABLE === 'categories' || FULL_ROUTE === '' || check_alias(FIRST_PARAMETER, 1) === 1)
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
	 * parse code
	 *
	 * @since 1.3
	 *
	 * @param $input string
	 * @return $output string
	 */

	protected function _parseCode($input = '')
	{
		$output = str_replace(array(
			'<code>',
			'</code>'
		), '||', $input);
		$parts = explode('||', $output);

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
	 * parse function
	 *
	 * @since 1.3
	 *
	 * @param $input string
	 * @return $output string
	 */

	protected function _parseFunction($input = '')
	{
		$output = str_replace(array(
			'<function>',
			'</function>'
		), '||', $input);
		$parts = explode('||', $output);
		$functionTerms = explode(', ', b('function_terms'));

		/* parse needed parts */

		foreach ($parts as $key => $value)
		{
			if ($key % 2)
			{
				/* json decode */

				$json = json_decode($value);

				/* catch function output */

				ob_start();
				foreach ($json as $function => $parameter)
				{
					/* validate allowed functions */

					if (!in_array($function, $functionTerms)) {
						call_user_func_array($function, $parameter);
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
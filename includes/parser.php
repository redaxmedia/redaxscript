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
		'php' => array(
			'function' => '_parsePhp',
			'position' => ''
		)
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
			$position = $this->_tag[$key]['position'] = strpos($this->_output, '<' . $key . '>');

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
			$output = substr($output, 0, $this->_tag['break']['position']);
			if ($this->_route)
			{
				$output .= anchor_element('internal', '', 'link_read_more', l('read_more'), $this->_route);
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
		$code_parts = explode('||', $output);

		/* parse needed parts */

		foreach ($code_parts as $key => $value)
		{
			if ($key % 2)
			{
				$code_parts[$key] = '<code class="box_code">' . trim(htmlspecialchars($value)) . '</code>';
			}
		}
		$output = implode($code_parts);
		return $output;
	}

	/**
	 * parse php
	 *
	 * @since 1.3
	 *
	 * @param $input string
	 * @return $output string
	 */

	protected function _parsePhp($input = '')
	{
		$output = str_replace(array(
			'<php>',
			'</php>'
		), '||', $input);
		$php_parts = explode('||', $output);
		$function_terms = explode(', ', b('function_terms'));

		/* parse needed parts */

		foreach ($php_parts as $key => $value)
		{
			if ($key % 2)
			{
				/* validate allowed function */

				$valid = 1;
				foreach ($function_terms as $term)
				{
					if (strpos($value, $term))
					{
						$valid = 0;
					}
				}

				/* call valid function */

				if ($valid === 1)
				{
					ob_start();
					eval($value);
					$php_parts[$key] = ob_get_clean();

				}
			}
		}
		$output = implode($php_parts);
		return $output;
	}
}
?>
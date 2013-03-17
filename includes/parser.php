<?php

/**
 * Redaxscript_Parser
 *
 * @param string $input
 * @return string
 */

class Redaxscript_Parser
{
	protected $tags = array(
		'<break>' => 'parse_break',
		'<code>' => 'parse_code',
		'<php>' => 'parse_php'
		);
	public $output;
	public $route;
	public $position;

	/* construct */

	function __construct($input = '', $route = '')
	{
		$this->output = $input;
		$this->route = $route;
		$this->parse_call();
	}

	/* parse call */

	public function parse_call()
	{
		foreach($this->tags as $tag => $function)
		{
			$this->position[$tag] = strpos($this->output, $tag);

			/* call related function if tag */

			if ($this->position[$tag] > -1)
			{
				$this->output = $this->$function($this->output);
			}
		}
	}

	/* parse break */

	public function parse_break($input = '')
	{
		$output = str_replace('<break>', '', $input);
		if (LAST_TABLE == 'categories' || FULL_ROUTE == '' || check_alias(FIRST_PARAMETER, 1) == 1)
		{		
			$output = substr($output, 0, $this->position['<break>']);
			if ($this->route)
			{
				$output .= anchor_element('internal', '', 'link_read_more', l('read_more'), $this->route);
			}
		}
		return $output;
	}

	/* parse code */

	public function parse_code($input = '')
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

	/* parse php */

	public function parse_php($input = '')
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

				if ($valid == 1)
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
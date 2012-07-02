<?php

/* parser */

function parser($input = '')
{
	$position_break = strpos($input, '<break>');
	$position_code = strpos($input, '<code>');
	$position_function = strpos($input, '<function>');

	/* document break */

	if ($position_break > -1)
	{
		$output = str_replace('<break>', '', $input);
		if (LAST_TABLE == 'categories' || FULL_STRING == '' || check_alias(FULL_STRING, 1) == 1)
		{
			$output = substr($output, 0, $position_break);
		}
	}
	else
	{
		$output = $input;
	}

	/* code quote */

	if ($position_code > -1)
	{
		$output = str_replace(array(
			'<code>',
			'</code>'
		), '||', $output);
		$output = explode('||', $output);
		$counter = count($output);
		for ($i = 1; $i < $counter; $i = $i + 2)
		{
			$output[$i] = trim($output[$i]);
			$output[$i] = htmlspecialchars($output[$i]);
			$output[$i] = '<code class="box_code">' . $output[$i] . '</code>';
		}
		$output = implode($output);
	}

	/* function call */

	if ($position_function > -1)
	{
		$output = str_replace(array(
			'<function>',
			'</function>'
		), '||', $output);
		$output = explode('||', $output);
		$counter = count($output);
		$function_terms = b('function_terms');
		for ($i = 1; $i < $counter; $i = $i + 2)
		{
			$function = explode('|', $output[$i]);

			/* validate allowed function call */

			$function_terms = explode(', ', $function_terms);
			$function_parts = explode('_', $function[0]);
			if ($function_parts && $function_terms)
			{
				$function_intersect = array_intersect($function_parts, $function_terms);
			}
			if ($function_intersect[0] == '' && function_exists($function[0]))
			{
				ob_start();

				/* explode parameter */

				$parameter = explode('->', $function[1]);
				if ($parameter)
				{
					foreach ($parameter as $key => $value)
					{
						/* explode arrays */

						$position_array = strpos($value, 'array');
						if ($position_array > -1)
						{
							$array = array();
							$array_string = substr($value, 6, -1);
							$array_parts = explode(', ', $array_string);

							/* fetch array parts */

							foreach ($array_parts as $part)
							{
								$position_part = strpos($part, '=>');
								if ($position_part > -1)
								{
									$array_key = trim(substr($part, 0, $position_part));
									$array_value = trim(substr($part, $position_part + 2));
									$array[$array_key] = $array_value;
								}
								else
								{
									$array[] = trim($part);
								}
							}
							$parameter[$key] = $array;
						}
					}
				}
				$result = call_user_func_array($function[0], $parameter);
				$output[$i] = ob_get_clean();
				if ($output[$i] == '')
				{
					$output[$i] = $result;
				}
			}
		}
		$output = implode($output);
	}
	return $output;
}
?>
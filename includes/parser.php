<?php

/**
 * parser
 *
 * @param string $input
 * @return string
 */

function parser($input = '')
{
	/* check position */

	$position_break = strpos($input, '<break>');
	$position_code = strpos($input, '<code>');
	$position_php = strpos($input, '<php>');

	/* if document break */

	if ($position_break > -1)
	{
		$output = str_replace('<break>', '', $input);
		if (LAST_TABLE == 'categories' || FULL_ROUTE == '' || check_alias(FIRST_PARAMETER, 1) == 1)
		{
			$output = substr($output, 0, $position_break);
		}
	}

	/* else fallback */

	else
	{
		$output = $input;
	}

	/* if code quote */

	if ($position_code > -1)
	{
		$output = str_replace(array(
			'<code>',
			'</code>'
		), '||', $output);
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
	}

	/* if php code */

	if ($position_php > -1)
	{
		$output = str_replace(array(
			'<php>',
			'</php>'
		), '||', $output);
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
	}
	return $output;
}
?>
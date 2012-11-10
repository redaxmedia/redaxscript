<?php

/* anchor element */

function anchor_element($type = '', $id = '', $class = '', $name = '', $value = '', $title = '', $code = '')
{
	/* build attribute strings */

	if ($id)
	{
		$selector_string = ' id="' . $id . '"';
	}
	if ($class)
	{
		$selector_string .= ' class="' . $class . '"';
	}
	if ($value)
	{
		$value_string = ' href="';

		/* switch type */

		switch ($type)
		{
			case 'external':
				$value = clean_url($value);
				if (check_protocol($value) == '')
				{
					$value_string .= 'http://';
				}
				break;
			case 'internal':
				$value_string .= REWRITE_ROUTE;
				break;
			case 'email':
				$value = clean_email($value);
				$value_string .= 'mailto:';
				break;
		}
		$value_string .= $value . '"';
	}
	if ($value_string == ' href=""')
	{
		$value_string = '';
	}
	$title_string = ' title="' . $title . '"';
	if ($code)
	{
		$code_string = ' ' . $code;
	}

	/* collect output */

	$output = '<a' . $selector_string . $value_string . $title_string . $code_string . '>' . $name . '</a>';
	return $output;
}

/* form element */

function form_element($type = '', $id = '', $class = '', $name = '', $value = '', $label = '', $code = '')
{
	/* build attribute strings */

	if ($id)
	{
		$selector_string = ' id="' . $id . '"';
	}
	if ($class)
	{
		$selector_string .= ' class="' . $class . '"';
	}
	if ($name)
	{
		$name_string = ' name="' . $name . '"';
	}
	if ($value)
	{
		$value_string = ' value="' . $value . '"';
	}
	if ($id && $label)
	{
		$label_string = '<label class="label" for="' . $id . '">' . $label . l('colon') . '</label>';
	}
	if ($code)
	{
		$code_string = ' ' . $code;
	}

	/* switch type */

	switch ($type)
	{
		case 'form':
			$output = '<form' . $selector_string . $code_string . '>';
			break;
		case 'fieldset':
			$output = '<fieldset' . $selector_string . '>';
			if ($label)
			{
				$output .= '<legend class="legend">' . $label . '</legend>';
			}
			break;
		case 'checkbox':
		case 'date':
		case 'email':
		case 'number':
		case 'password':
		case 'radio':
		case 'range':
		case 'search':
		case 'tel':
		case 'text':
		case 'time':
		case 'url':
			$output = $label_string;
		case 'hidden':
		case 'file':
		case 'reset':
		case 'submit':
			$output .= '<input type="' . $type . '"' . $selector_string . $name_string . $value_string . $code_string . ' />';
			break;
		case 'button':
			$output .= '<button type="submit"' . $selector_string . $name_string . $value_string . $code_string . '><span><span>' . $value . '</span></span></button>';
			break;
		case 'textarea':
			$output = $label_string . '<textarea' . $selector_string . $name_string . $code_string . '>' . $value . '</textarea>';
			break;
	}
	return $output;
}

/* select element */

function select_element($id = '', $class = '', $name = '', $options = '', $select = '', $label = '', $code = '')
{
	/* build attribute strings */

	if ($id)
	{
		$selector_string = ' id="' . $id . '"';
	}
	if ($class)
	{
		$selector_string .= ' class="' . $class . '"';
	}
	if ($name)
	{
		$name_string = ' name="' . $name;
	}
	if ($code)
	{
		$code_string = ' ' . $code;
		$position_multiple = strpos($code, 'multiple="multiple"');
	}

	/* handle multiple select */

	if ($position_multiple > -1)
	{
		if ($name)
		{
			$name_string .= '[]';
		}
		$size = count($options);
		if ($size)
		{
			$size_string = ' size="' . $size . '"';
		}
		$select = explode(', ', $select);
	}
	if ($name)
	{
		$name_string .= '"';
	}
	if ($id && $label)
	{
		$label_string = '<label class="label" for="' . $id . '">' . $label . l('colon') . '</label>';
	}

	/* collect output */

	$output = $label_string . '<select' . $selector_string . $name_string . $size_string . $code_string . '>';
	foreach ($options as $key => $value)
	{
		if (is_numeric($key))
		{
			$key = $value;
		}
		$output .= '<option value="' . $value . '"';
		if ($value == $select || is_array($select) && in_array($value, $select))
		{
			$output .= ' selected="selected"';
		}
		$output .= '>' . $key . '</option>';
	}
	$output .= '</select>';
	return $output;
}

/* select date */

function select_date($id = '', $class = '', $name = '', $date = '', $format = '', $start = '', $end = '', $label = '', $code = '')
{
	/* build attribute strings */

	if ($id)
	{
		$selector_string = ' id="' . $id . '"';
	}
	if ($class)
	{
		$selector_string .= ' class="' . $class . '"';
	}
	if ($name)
	{
		$name_string = ' name="' . $name . '"';
	}
	if ($id && $label)
	{
		$label_string = '<label class="label" for="' . $id . '">' . $label . l('colon') . '</label>';
	}
	if ($code)
	{
		$code_string = ' ' . $code;
	}

	/* collect output */

	$output = $label_string . '<select' . $selector_string . $name_string . $code_string . '>';
	$select = $date ? date($format, strtotime($date)) : date($format);
	for ($i = $start; $i < $end; $i++)
	{
		$value = sprintf('%02d', $i);
		$output .= '<option value="' . $value . '"';
		if ($i == $select)
		{
			$output .= ' selected="selected"';
		}
		$output .= '>' . $value . '</option>';
	}
	$output .= '</select>';
	return $output;
}

/* object element */

function object_element($type = '', $id = '', $class = '', $name = '', $value = '', $data = '', $code = '')
{
	/* build attribute strings */

	if ($id)
	{
		$selector_string = ' id="' . $id . '"';
	}
	if ($class)
	{
		$selector_string .= ' class="' . $class . '"';
	}
	if ($name && $value)
	{
		$param_string = ' <param name="' . $name . '" value="' . $value . '" />';
	}
	if ($code)
	{
		$code_string = ' ' . $code;
	}

	/* collect output */

	if ($type)
	{
		$output = '<object type="' . $type . '"' . $selector_string . ' data="' . $data . '"' . $code_string . '>' . $param_string . '</object>';
	}
	return $output;
}
?>
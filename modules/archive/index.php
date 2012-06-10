<?php

/* archive */

function archive()
{
	$query = 'SELECT id, title, alias, description, date, category, access FROM ' . PREFIX . 'articles WHERE (language = \'' . LANGUAGE . '\' || language = \'\') && status = 1 ORDER BY date DESC';
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if ($result == '' || $num_rows == '')
	{
		$error = l('article_no') . l('point');
	}
	else if ($result)
	{
		$month_names = explode(', ', l('month_names'));
		$last = 0;
		while ($r = mysql_fetch_assoc($result))
		{
			/* check for access */

			$access = $r['access'];
			$check_access = check_access($access, MY_GROUPS);
			if ($check_access == 1)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}
				if ($description == '')
				{
					$description = $title;
				}
				$year = substr($date, 0, 4);
				$month = substr($date, 5, 2) - 1;
				if ($category == 0)
				{
					$string = $alias;
				}
				else
				{
					$string = build_string('articles', $id);
				}

				/* collect output */

				if ($last <> $month + $year)
				{
					if ($last > 0)
					{
						$output .= '</ul></fieldset>';
					}
					$output .= form_element('fieldset', '', 'box_archive', '', '', '<span class="title_content_sub">' . $month_names[$month] . ' ' . $year . '</span>') . '<ul class="list_default">';
				}
				$output .= '<li>' . anchor_element('internal', '', '', $title, $string, $description) . '</li>';
				$last = $month + $year;
			}
			else
			{
				$counter++;
			}
		}
		if ($num_rows == $counter)
		{
			$error = l('access_no') . l('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = form_element('fieldset', '', 'box_archive', '', '', '<span class="title_content_sub">' . l('error') . '</span>') . '<ul class="list_default">';
		$output .= '<li>' . $error . '</li>';
	}
	$output .= '</ul></fieldset>';
	return $output;
}
?>
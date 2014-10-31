<?php

/**
 * archive
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @return string
 */

function archive()
{
	$query = 'SELECT id, title, alias, description, date, category, access FROM ' . PREFIX . 'articles WHERE (language = \'' . LANGUAGE . '\' || language = \'\') && status = 1 ORDER BY date DESC';
	$result = Redaxscript\Db::forTablePrefix('articles')->rawQuery($query)->findArray();
	$num_rows = count($result);
	if ($result == '' || $num_rows == '')
	{
		$error = l('article_no') . l('point');
	}
	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		$last = 0;
		foreach ($result as $r)
		{
			/* check for access */

			$access = $r['access'];

			/* if access granted */

			if ($accessValidator->validate($access, MY_GROUPS) === Redaxscript\Validator\Validator::PASSED)
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

				/* build route */

				if ($category == 0)
				{
					$route = $alias;
				}
				else
				{
					$route = build_route('articles', $id);
				}

				/* collect output */

				if ($last <> $month + $year)
				{
					if ($last > 0)
					{
						$output .= '</ul></fieldset>';
					}
					$output .= form_element('fieldset', '', 'set_archive', '', '', '<span class="title_content_sub title_archive_sub">' . l($month, '_month') . ' ' . $year . '</span>') . '<ul class="list_default list_archive">';
				}
				$output .= '<li>' . anchor_element('internal', '', '', $title, $route, $description) . '</li>';
				$last = $month + $year;
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = l('access_no') . l('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = form_element('fieldset', '', 'set_archive', '', '', '<span class="title_content_sub title_archive_sub">' . l('error') . '</span>') . '<ul class="list_default list_archive">';
		$output .= '<li>' . $error . '</li>';
	}
	$output .= '</ul></fieldset>';
	return $output;
}

<?php

/**
 * search
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Search
 * @author Henry Ruhs
 */

function search()
{
	search_form();
}

/**
 * search form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Search
 * @author Henry Ruhs
 */

function search_form()
{
	$output = Redaxscript\Hook::trigger(__FUNCTION__ . '_start');

	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$code_disabled = ' disabled="disabled"';
	}

	/* collect output */

	$output .= form_element('form', '', 'js_validate_search form_search', '', '', '', 'method="post"');
	$output .= form_element('search', '', 'js_search field_search', 'search_terms', '', '', 'maxlength="50" tabindex="1" placeholder="' . l('search_terms') . '"' . $code_disabled);

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'search_post');
	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'button_search', 'search_post', l('search'), '', $code_disabled);
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger(__FUNCTION__ . '_end');
	echo $output;
}

/**
 * search post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Search
 * @author Henry Ruhs
 */

function search_post()
{
	/* clean post */

	if (ATTACK_BLOCKED < 10)
	{
		$search_terms = clean($_POST['search_terms'], 1);
	}

	/* validate post */

	if (strlen($search_terms) < 3 || $search_terms == l('search_terms'))
	{
		$error = l('input_incorrect');
	}

	/* query results */

	else
	{
		$search = array_filter(explode(' ', $search_terms));
		$search_keys = array_keys($search);
		$last = end($search_keys);

		/* query search */

		$query = 'SELECT id, title, alias, description, date, category, access FROM ' . PREFIX . 'articles WHERE (language = \'' . LANGUAGE . '\' || language = \'\') && status = 1';
		if ($search)
		{
			$query .= ' && (';
			foreach ($search as $key => $value)
			{

				$query .= 'title LIKE \'%' . $value . '%\' || description LIKE \'%' . $value . '%\' || keywords LIKE \'%' . $value . '%\' || text LIKE \'%' . $value . '%\'';
				if ($last != $key)
				{
					$query .= ' || ';
				}
			}
			$query .= ')';
		}
		$query .= ' ORDER BY date DESC LIMIT 50';
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		if ($result == '' || $num_rows == '')
		{
			$error = l('search_no');
		}

		/* collect output */

		else if ($result)
		{
			$accessValidator = new Redaxscript\Validator\Access();
			$output = '<h2 class="title_content title_search_result">' . l('search') . '</h2>';
			$output .= form_element('fieldset', '', 'set_search_result', '', '', '<span class="title_content_sub title_search_result_sub">' . l('articles') . '</span>') . '<ol class="list_search_result">';
			while ($r = mysql_fetch_assoc($result))
			{
				$access = $r['access'];
				$check_access = $accessValidator->validate($access, MY_GROUPS);

				/* if access granted */

				if ($check_access == 1)
				{
					if ($r)
					{
						foreach ($r as $key => $value)
						{
							$$key = stripslashes($value);
						}
					}

					/* prepare metadata */

					if ($description == '')
					{
						$description = $title;
					}
					$date = date(s('date'), strtotime($date));

					/* build route */

					if ($category == 0)
					{
						$route = $alias;
					}
					else
					{
						$route = build_route('articles', $id);
					}

					/* collect item output */

					$output .= '<li class="item_search_result">' . anchor_element('internal', '', 'link_search_result', $title, $route, $description) . '<span class="date_search_result">' . $date . '</span></li>';
				}
				else
				{
					$counter++;
				}
			}
			$output .= '</ol></fieldset>';

			/* handle access */

			if ($num_rows == $counter)
			{
				$error = l('access_no');
			}
		}
	}

	/* handle error */

	if ($error)
	{
		notification(l('something_wrong'), $error);
	}
	else
	{
		echo $output;
	}
}
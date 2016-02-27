<?php
use Redaxscript\Language;

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

	$search_terms = clean($_POST['search_terms'], 5);
	$table = clean($_POST['table']);

	/* validate post */

	if (strlen($search_terms) < 3 || $search_terms == l('search_terms'))
	{
		$error = l('input_incorrect');
	}
	else
	{
		/* fetch result */

		$result = Redaxscript\Db::forTablePrefix($table)
			->where('status', 1)
			->whereRaw('(language = ? OR language is ?)', array(
				Redaxscript\Registry::get('language'),
				null
			))
			->whereLikeMany(array(
				'title',
				'description',
				'keywords',
				'text'
			), array(
				'%' . $search_terms . '%',
				'%' . $search_terms . '%',
				'%' . $search_terms . '%',
				'%' . $search_terms . '%'
			))
			->orderByDesc('date')
			->findArray();

		/* process result */

		$num_rows = count($result);
		if (!$result)
		{
			$error = l('search_no');
		}
		else if ($result)
		{
			$accessValidator = new Redaxscript\Validator\Access();
			$output = '<h2 class="rs-title-content title-search-result">' . l('search') . '</h2>';
			$output .= '<ol class="rs-list-search-result">';
			foreach ($result as $r)
			{
				$access = $r['access'];

				/* access granted */

				if ($accessValidator->validate($access, MY_GROUPS) === Redaxscript\Validator\ValidatorInterface::PASSED)
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

					if ($table == 'categories' && $parent == 0 || $table == 'articles' && $category == 0)
					{
						$route = $alias;
					}
					else
					{
						$route = build_route($table, $id);
					}

					/* collect item output */

					$output .= '<li class="rs-item-search-result">' . anchor_element('internal', '', 'link_search_result', $title, $route, $description) . '<span class="rs-date-search-result">' . $date . '</span></li>';
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
		$messenger = new \Redaxscript\Messenger();
		echo $messenger->error($error, Language::get('something_wrong'));
	}
	else
	{
		echo $output;
	}
}

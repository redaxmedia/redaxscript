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
 *
 * @param string $table
 */

function search_form($table = 'articles')
{
	$output = Redaxscript\Hook::trigger('searchFormStart');

	/* html elements */

	$formElement = new Redaxscript\Html\Form(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
	$formElement->init(array(
		'form' => array(
			'class' => 'rs-js-validate-search rs-form-search'
		)
	));

	/* create the form */

	$formElement
		->search(array(
			'name' => 'search_terms',
			'placeholder' => Redaxscript\Language::get('search_terms'),
			'tabindex' => '1'
		))
		->hidden(array(
			'name' => 'table',
			'value' => $table
		))
		->token()
		->submit(Redaxscript\Language::get('search'), array(
			'class' => 'rs-button-search',
			'name' => 'search_post'
		));

	/* collect output */

	$output .= $formElement;
	$output .= Redaxscript\Hook::trigger('searchFormEnd');
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
			->whereIn('language', array(
				Redaxscript\Registry::get('language'),
				''
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
			$output .= form_element('fieldset', '', 'set_search_result', '', '', '') . '<ol class="rs-list-search-result">';
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
		notification(l('something_wrong'), $error);
	}
	else
	{
		echo $output;
	}
}

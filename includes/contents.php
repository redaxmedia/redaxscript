<?php

/**
 * contents
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 */

function contents()
{
	$output = Redaxscript\Hook::trigger('contentStart');
	$aliasValidator = new Redaxscript\Validator\Alias();

	/* query articles */

	$articles = Redaxscript\Db::forTablePrefix('articles')->where('status', 1);
	$articles->whereIn('language', array(
		Redaxscript\Registry::get('language'),
		''
	));

	/* handle sibling */

	if (LAST_ID)
	{
		$sibling = Redaxscript\Db::forTablePrefix(LAST_TABLE)->where('id', LAST_ID)->findOne()->sibling;

		/* query sibling collection */

		$sibling_array = Redaxscript\Db::forTablePrefix('articles')->whereIn('sibling', array(
			LAST_ID,
			$sibling > 0 ? $sibling : null
		))->where('language', Redaxscript\Registry::get('language'))->select('id')->findArrayFlat();

		/* process sibling array */

		foreach ($sibling_array as $value)
		{
			$id_array[] = $value;
		}
	}

	/* handle article */

	if (ARTICLE)
	{
		$id_array[] = $sibling;
		$id_array[] = ARTICLE;
		$articles->whereIn('id', $id_array);
	}

	/* else handle category */

	else if (CATEGORY)
	{
		if (!$id_array)
		{
			if ($sibling > 0)
			{
				$id_array[] = $sibling;
			}
			else
			{
				$id_array[] = CATEGORY;
			}
		}
		$articles->whereIn('category', $id_array)->orderGlobal('rank');

		/* handle sub parameter */

		$result = $articles->findArray();
		if ($result)
		{
			$num_rows = count($result);
			$sub_maximum = ceil($num_rows / s('limit'));
			$sub_active = LAST_SUB_PARAMETER;

			/* sub parameter */

			if (LAST_SUB_PARAMETER > $sub_maximum || LAST_SUB_PARAMETER == '')
			{
				$sub_active = 1;
			}
			else
			{
				$offset_string = ($sub_active - 1) * s('limit') . ', ';
			}
		}
		$articles->limit($offset_string . s('limit'));
	}
	else
	{
		$articles->limit(0);
	}

	/* query result */

	$result = $articles->findArray();
	$num_rows_active = count($result);

	/* handle error */

	if (CATEGORY && $num_rows == '')
	{
		$error = l('article_no');
	}
	else if ($result == '' || $num_rows_active == '' || CONTENT_ERROR)
	{
		$error = l('content_not_found');
	}

	/* collect output */

	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
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
				if (LAST_TABLE == 'categories' || FULL_ROUTE == '' || $aliasValidator->validate(FIRST_PARAMETER, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED)
				{
					$route = build_route('articles', $id);
				}

				/* parser object */

				$parser = new Redaxscript\Parser(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
				$parser->init($text, array(
					'className' => array(
						'readmore' => 'rs-link-read_more',
						'codequote' => 'rs-js-code-quote rs-box-code'
					),
					'route' => $route
				));

				/* collect headline output */

				$output .= Redaxscript\Hook::trigger('contentFragmentStart', $r);
				if ($headline == 1)
				{
					$output .= '<h2 class="rs-title-content" id="article-' . $alias . '">';
					if (LAST_TABLE == 'categories' || FULL_ROUTE == ''
						|| $aliasValidator->validate(FIRST_PARAMETER, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED
					)
					{
						$output .= anchor_element('internal', '', '', $title, $route);
					}
					else
					{
						$output .= $title;
					}
					$output .= '</h2>';
				}

				/* collect box output */

				$output .= '<div class="rs-box-content">' . $parser->getOutput();
				$output .= '</div>' . Redaxscript\Hook::trigger('contentFragmentEnd', $r);

				/* prepend admin dock */

				if (LOGGED_IN == TOKEN && FIRST_PARAMETER != 'logout')
				{
					$output .= admin_dock('articles', $id);
				}

				/* infoline */

				if ($infoline == 1)
				{
					$output .= infoline('articles', $id, $author, $date);
				}
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if (LAST_TABLE == 'categories')
		{
			if ($num_rows_active == $counter)
			{
				$error = l('access_no');
			}
		}
		else if (LAST_TABLE == 'articles' && $counter == 1)
		{
			$error = l('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		notification(l('something_wrong'), $error);
	}
	else
	{
		$output .= Redaxscript\Hook::trigger('contentEnd');
		echo $output;

		/* call comments as needed */

		if (ARTICLE)
		{
			/* comments replace */

			if ($comments == 1 && (COMMENTS_REPLACE == 1 || Redaxscript\Registry::get('commentReplace')))
			{
				Redaxscript\Hook::trigger('commentReplace');
			}

			/* else native comments */

			else if ($comments > 0)
			{
				$route = build_route('articles', ARTICLE);
				comments(ARTICLE, $route);

				/* comment form */

				if ($comments == 1 || (COMMENTS_NEW == 1 && $comments == 3))
				{
					comment_form(ARTICLE, $language);
				}
			}
		}
	}

	/* call pagination as needed */

	if ($sub_maximum > 1 && s('pagination') == 1)
	{
		$route = build_route('categories', CATEGORY);
		pagination($sub_active, $sub_maximum, $route);
	}
}

/**
 * extras
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param mixed $filter
 */

function extras($filter = '')
{
	if ($filter == '')
	{
		$output .= Redaxscript\Hook::trigger('extraStart');
	}

	/* query extras */

	$extras = Redaxscript\Db::forTablePrefix('extras')
		->whereIn('language', array(
			Redaxscript\Registry::get('language'),
			''
		));

	/* has filter */

	if ($filter)
	{
		$id = Redaxscript\Db::forTablePrefix('extras')->where('alias', $filter)->findOne()->id;

		/* handle sibling */

		$sibling = Redaxscript\Db::forTablePrefix('extras')->where('id', $id)->findOne()->sibling;

		/* query sibling collection */

		$sibling_array = Redaxscript\Db::forTablePrefix('extras')->whereIn('sibling', array(
			$id,
			$sibling > 0 ? $sibling : null
		))->where('language', Redaxscript\Registry::get('language'))->select('id')->findArrayFlat();

		/* process sibling array */

		foreach ($sibling_array as $value)
		{
			$id_array[] = $value;
		}
		$id_array[] = $sibling;
		$id_array[] = $id;
	}
	else
	{
		$id_array = $extras->where('status', 1)->orderByAsc('rank')->select('id')->findArrayFlat();
	}

	/* query result */

	$result = $extras->whereIn('id', $id_array)->findArray();

	/* collect output */

	if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
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

				/* show if cagegory or article matched */

				if ($category == CATEGORY || $article == ARTICLE || ($category == 0 && $article == 0))
				{
					/* parser object */

					$parser = new Redaxscript\Parser(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
					$parser->init($text, array(
						'className' => array(
							'readmore' => 'rs-link-read_more',
							'codequote' => 'rs-js-code-quote rs-box-code'
						),
						'route' => $route
					));

					/* collect headline output */

					$output .= Redaxscript\Hook::trigger('extraFragmentStart', $r);
					if ($headline == 1)
					{
						$output .= '<h3 class="rs-title-extra" id="extra-' . $alias . '">' . $title . '</h3>';
					}

					/* collect box output */

					$output .= '<div class="rs-box-extra">' . $parser->getOutput() . '</div>' . Redaxscript\Hook::trigger('extraFragmentEnd', $r);

					/* prepend admin dock */

					if (LOGGED_IN == TOKEN && FIRST_PARAMETER != 'logout')
					{
						$output .= admin_dock('extras', $id);
					}
				}
			}
		}
	}
	if ($filter == '')
	{
		$output .= Redaxscript\Hook::trigger('extraEnd');
	}
	echo $output;
}

/**
 * infoline
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param string $table
 * @param integer $id
 * @param string $author
 * @param string $date
 *
 * @return string
 */

function infoline($table = '', $id = '', $author = '', $date = '')
{
	$output = Redaxscript\Hook::trigger('infolineStart');
	$time = date(s('time'), strtotime($date));
	$date = date(s('date'), strtotime($date));
	if ($table == 'articles')
	{
		$comments_total = Redaxscript\Db::forTablePrefix('comments')->where('article', $id)->count();
	}

	/* collect output */

	$output .= '<div class="rs-box-infoline rs-box-infoline_' . $table . '">';

	/* collect author output */

	if ($table == 'articles')
	{
		$output .= '<span class="rs-infoline-posted-by">' . l('posted_by') . ' ' . $author . '</span>';
		$output .= '<span class="rs-infoline-on"> ' . l('on') . ' </span>';
	}

	/* collect date and time output */

	$output .= '<span class="rs-infoline-date">' . $date . '</span>';
	$output .= '<span class="rs-infoline-at"> ' . l('at') . ' </span>';
	$output .= '<span class="rs-infoline-time">' . $time . '</span>';

	/* collect comment output */

	if ($comments_total)
	{
		$output .= '<span class="rs-divider">' . s('divider') . '</span><span class="rs-infoline-total">' . $comments_total . ' ';
		if ($comments_total == 1)
		{
			$output .= l('comment');
		}
		else
		{
			$output .= l('comments');
		}
		$output .= '</span>';
	}
	$output .= '</div>';
	$output .= Redaxscript\Hook::trigger('infolineEnd');
	return $output;
}

/**
 * pagination
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param integer $sub_active
 * @param integer $sub_maximum
 * @param string $route
 */

function pagination($sub_active = '', $sub_maximum = '', $route = '')
{
	$output = Redaxscript\Hook::trigger('paginationStart');
	$output .= '<ul class="rs-list-pagination">';

	/* collect first and previous output */

	if ($sub_active > 1)
	{
		$first_route = $route;
		$previous_route = $route . '/' . ($sub_active - 1);
		$output .= '<li class="rs-item-first">' . anchor_element('internal', '', '', l('first'), $first_route) . '</li>';
		$output .= '<li class="rs-item-previous">' . anchor_element('internal', '', '', l('previous'), $previous_route, '', 'rel="previous"') . '</li>';
	}

	/* collect center output */

	$j = 2;
	if ($sub_active == 2 || $sub_active == $sub_maximum - 1)
	{
		$j++;
	}
	if ($sub_active == 1 || $sub_active == $sub_maximum)
	{
		$j = $j + 2;
	}
	for ($i = $sub_active - $j; $i < $sub_active + $j; $i++)
	{
		if ($i == $sub_active)
		{
			$j++;
			$output .= '<li class="rs-item-number rs-item-active"><span>' . $i . '</span></li>';
		}
		else if ($i > 0 && $i < $sub_maximum + 1)
		{
			$output .= '<li class="rs-item-number">' . anchor_element('internal', '', '', $i, $route . '/' . $i) . '</li>';
		}
	}

	/* collect next and last output */

	if ($sub_active < $sub_maximum)
	{
		$next_route = $route . '/' . ($sub_active + 1);
		$last_route = $route . '/' . $sub_maximum;
		$output .= '<li class="rs-item-next">' . anchor_element('internal', '', '', l('next'), $next_route, '', 'rel="next"') . '</li>';
		$output .= '<li class="rs-item-last">' . anchor_element('internal', '', '', l('last'), $last_route) . '</li>';
	}
	$output .= '</ul>';
	$output .= Redaxscript\Hook::trigger('paginationEnd');
	echo $output;
}

/**
 * notification
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Contents
 * @author Henry Ruhs
 *
 * @param string $title
 * @param string $text
 * @param string $action
 * @param string $route
 */

function notification($title = '', $text = '', $action = '', $route = '')
{
	$output = Redaxscript\Hook::trigger('notificationStart');

	/* detect needed mode */

	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin')
	{
		$button = 'rs-admin-button';
	}
	else
	{
		$button = 'rs-button-default';
	}

	/* collect output */

	if ($title)
	{
		$output .= '<h2 class="rs-title-content rs-title-notification">' . $title . '</h2>';
	}
	$output .= '<div class="rs-box-content rs-box-notification">';

	/* collect text output */

	if (is_string($text))
	{
		$text = array(
			$text
		);
	}
	foreach ($text as $value)
	{
		if ($value)
		{
			$output .= '<p class="rs-text_notification">' . $value . l('point') . '</p>';
		}
	}

	/* collect button output */

	if ($action && $route)
	{
		$output .= anchor_element('internal', '', 'rs-js-forward-notification ' . $button, $action, $route);
	}
	$output .= '</div>';
	$output .= Redaxscript\Hook::trigger('notificationEnd');
	echo $output;
}

/**
 * break up
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Replace
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function break_up($input = '')
{
	$search = array(
		chr(13) . chr(10),
		chr(13),
		chr(10)
	);
	$replace = '<br />';
	$output = str_replace($search, $replace, $input);
	return $output;
}

/**
 * truncate
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Replace
 * @author Henry Ruhs
 *
 * @param string $input
 * @param integer $length
 * @param string $end
 * @return string
 */

function truncate($input = '', $length = '', $end = '')
{
	$length -= mb_strlen($end);
	if (mb_strlen($input) > $length)
	{
		$output = trim(mb_substr($input, 0, $length)) . $end;
	}

	/* else fallback */

	else
	{
		$output = $input;
	}
	return $output;
}

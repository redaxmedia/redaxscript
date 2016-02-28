<?php
use Redaxscript\Language;

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
	$articles
		->whereRaw('(language = ? OR language is ?)', array(
			Redaxscript\Registry::get('language'),
			null
		));

	/* handle sibling */

	if (LAST_ID)
	{
		$sibling = Redaxscript\Db::forTablePrefix(LAST_TABLE)->where('id', LAST_ID)->findOne()->sibling;

		/* query sibling collection */

		$sibling_array = Redaxscript\Db::forTablePrefix(LAST_TABLE)->whereIn('sibling', array(
			LAST_ID,
			$sibling > 0 ? $sibling : null
		))->where('language', Redaxscript\Registry::get('language'))->select('id')->findFlatArray();

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
		$error = Redaxscript\Language::get('article_no');
	}
	else if ($result == '' || $num_rows_active == '' || CONTENT_ERROR)
	{
		$error = Redaxscript\Language::get('content_not_found');
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
					'route' => $route
				));

				/* collect headline output */

				$output .= Redaxscript\Hook::trigger('contentFragmentStart', $r);
				if ($headline == 1)
				{
					$output .= '<h2 class="rs-title-content" id="article-' . $alias . '">';
					if (LAST_TABLE == 'categories' || FULL_ROUTE == '' || $aliasValidator->validate(FIRST_PARAMETER, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED
					)
					{
						$output .= '<a href="' . Redaxscript\Registry::get('rewriteRoute') . $route . '">' . $title . '</a>';
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
				$error = Language::get('access_no');
			}
		}
		else if (LAST_TABLE == 'articles' && $counter == 1)
		{
			$error = Language::get('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		/* show error */

		$messenger = new \Redaxscript\Messenger();
		echo $messenger->error($error, Language::get('something_wrong'));
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
					$commentForm = new Redaxscript\View\CommentForm();
					echo $commentForm->render(ARTICLE);
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
		->whereRaw('(language = ? OR language is ?)', array(
			Redaxscript\Registry::get('language'),
			null
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
		))->where('language', Redaxscript\Registry::get('language'))->select('id')->findFlatArray();

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
		$id_array = $extras->where('status', 1)->orderByAsc('rank')->select('id')->findFlatArray();
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
		$output .= '<span class="rs-infoline-posted-by">' . Redaxscript\Language::get('posted_by') . ' ' . $author . '</span>';
		$output .= '<span class="rs-infoline-on"> ' . Redaxscript\Language::get('on') . ' </span>';
	}

	/* collect date and time output */

	$output .= '<span class="rs-infoline-date">' . $date . '</span>';
	$output .= '<span class="rs-infoline-at"> ' . Redaxscript\Language::get('at') . ' </span>';
	$output .= '<span class="rs-infoline-time">' . $time . '</span>';

	/* collect comment output */

	if ($comments_total)
	{
		$output .= '<span class="rs-divider">' . s('divider') . '</span><span class="rs-infoline-total">' . $comments_total . ' ';
		if ($comments_total == 1)
		{
			$output .= Redaxscript\Language::get('comment');
		}
		else
		{
			$output .= Redaxscript\Language::get('comments');
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
		$output .= '<li class="rs-item-first"><a href="' . Redaxscript\Registry::get('rewriteRoute') . $first_route . '">' . Redaxscript\Language::get('first') . '</a></li>';
		$output .= '<li class="rs-item-previous"><a href="' . Redaxscript\Registry::get('rewriteRoute') . $previous_route . '" rel="previous">' . Redaxscript\Language::get('previous') . '</a></li>';
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
			$output .= '<li class="rs-item-number"><a href="' . Redaxscript\Registry::get('rewriteRoute') . $route . '/' . $i . '">' . $i . '</a></li>';
		}
	}

	/* collect next and last output */

	if ($sub_active < $sub_maximum)
	{
		$next_route = $route . '/' . ($sub_active + 1);
		$last_route = $route . '/' . $sub_maximum;
		$output .= '<li class="rs-item-next"><a href="' . Redaxscript\Registry::get('rewriteRoute') . $next_route . '" rel="next">' . Redaxscript\Language::get('next') . '</a></li>';
		$output .= '<li class="rs-item-next"><a href="' . Redaxscript\Registry::get('rewriteRoute') . $last_route . '">' . Redaxscript\Language::get('last') . '</a></li>';
	}
	$output .= '</ul>';
	$output .= Redaxscript\Hook::trigger('paginationEnd');
	echo $output;
}
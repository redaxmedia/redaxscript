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
	$lastId = Redaxscript\Registry::get('lastId');
	$lastTable = Redaxscript\Registry::get('lastTable');
	$categoryId = Redaxscript\Registry::get('categoryId');
	$articleId = Redaxscript\Registry::get('articleId');
	$firstParameter = Redaxscript\Registry::get('firstParameter');

	/* query articles */

	$articles = Redaxscript\Db::forTablePrefix('articles')->where('status', 1);
	$articles->whereLanguageIs(Redaxscript\Registry::get('language'));

	/* handle sibling */

	if ($lastId)
	{
		$sibling = Redaxscript\Db::forTablePrefix($lastTable)->where('id', $lastId)->findOne()->sibling;

		/* query sibling collection */

		$sibling_array = Redaxscript\Db::forTablePrefix($lastTable)->whereIn('sibling',
		[
			$lastId,
			$sibling > 0 ? $sibling : null
		])
		->where('language', Redaxscript\Registry::get('language'))->select('id')->findFlatArray();

		/* process sibling array */

		foreach ($sibling_array as $value)
		{
			$id_array[] = $value;
		}
	}

	/* handle article */

	if ($articleId)
	{
		$id_array[] = $sibling;
		$id_array[] = $articleId;
		$articles->whereIn('id', $id_array);
	}

	/* else handle category */

	else if ($categoryId)
	{
		if (!$id_array)
		{
			if ($sibling > 0)
			{
				$id_array[] = $sibling;
			}
			else
			{
				$id_array[] = $categoryId;
			}
		}
		$articles->whereIn('category', $id_array)->orderGlobal('rank');

		/* handle sub parameter */

		$result = $articles->findArray();
		if ($result)
		{
			$num_rows = count($result);
			$sub_maximum = ceil($num_rows / Redaxscript\Db::getSetting('limit'));
			$sub_active = Redaxscript\Registry::get('lastSubParameter');

			/* sub parameter */

			if (Redaxscript\Registry::get('lastSubParameter') > $sub_maximum || !Redaxscript\Registry::get('lastSubParameter'))
			{
				$sub_active = 1;
			}
			else
			{
				$offset_string = ($sub_active - 1) * Redaxscript\Db::getSetting('limit') . ', ';
			}
		}
		$articles->limit($offset_string . Redaxscript\Db::getSetting('limit'));
	}
	else
	{
		$articles->limit(0);
	}

	/* query result */

	$result = $articles->findArray();
	$num_rows_active = count($result);

	/* handle error */

	if ($categoryId && !$num_rows)
	{
		$error = Redaxscript\Language::get('article_no');
	}
	else if (!$result || !$num_rows_active || Redaxscript\Registry::get('contentError'))
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

			if ($accessValidator->validate($access, Redaxscript\Registry::get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}
				if ($lastTable == 'categories' || !Redaxscript\Registry::get('fullRoute') || $aliasValidator->validate($firstParameter, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED)
				{
					$route = build_route('articles', $id);
				}

				/* parser */

				$parser = new Redaxscript\Parser(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
				$parser->init($text,
				[
					'route' => $route
				]);

				/* collect headline output */

				$output .= Redaxscript\Hook::trigger('contentFragmentStart', $r);
				if ($headline == 1)
				{
					$output .= '<h2 class="rs-title-content" id="article-' . $alias . '">';
					if ($lastTable == 'categories' || !Redaxscript\Registry::get('fullRoute') || $aliasValidator->validate($firstParameter, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED
					)
					{
						$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . $route . '">' . $title . '</a>';
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

				if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token') && $firstParameter != 'logout')
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

		if ($lastTable == 'categories')
		{
			if ($num_rows_active == $counter)
			{
				$error = Language::get('access_no');
			}
		}
		else if ($lastTable == 'articles' && $counter == 1)
		{
			$error = Redaxscript\Language::get('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		/* show error */

		$messenger = new Redaxscript\Messenger(Redaxscript\Registry::getInstance());
		echo $messenger->error($error, Redaxscript\Language::get('something_wrong'));
	}
	else
	{
		$output .= Redaxscript\Hook::trigger('contentEnd');
		echo $output;

		/* call comments as needed */

		if ($articleId)
		{
			/* comments replace */

			if ($comments == 1 && Redaxscript\Registry::get('commentReplace'))
			{
				Redaxscript\Hook::trigger('commentReplace');
			}

			/* else native comments */

			else if ($comments > 0)
			{
				$route = build_route('articles', $articleId);
				comments($articleId, $route);

				/* comment form */

				if ($comments == 1 || (Redaxscript\Registry::get('commentNew') && $comments == 3))
				{
					$commentForm = new Redaxscript\View\CommentForm(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
					echo $commentForm->render($articleId);
				}
			}
		}
	}

	/* call pagination as needed */

	if ($sub_maximum > 1 && Redaxscript\Db::getSetting('pagination') == 1)
	{
		$route = build_route('categories', $categoryId);
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

function extras($filter)
{
	if (!$filter)
	{
		$output .= Redaxscript\Hook::trigger('extraStart');
	}
	$categoryId = Redaxscript\Registry::get('categoryId');
	$articleId = Redaxscript\Registry::get('articleId');
	$firstParameter = Redaxscript\Registry::get('firstParameter');

	/* query extras */

	$extras = Redaxscript\Db::forTablePrefix('extras')
		->whereLanguageIs(Redaxscript\Registry::get('language'));

	/* has filter */

	if ($filter)
	{
		$id = Redaxscript\Db::forTablePrefix('extras')->where('alias', $filter)->findOne()->id;

		/* handle sibling */

		$sibling = Redaxscript\Db::forTablePrefix('extras')->where('id', $id)->findOne()->sibling;

		/* query sibling collection */

		$sibling_array = Redaxscript\Db::forTablePrefix('extras')->whereIn('sibling',
		[
			$id,
			$sibling > 0 ? $sibling : null
		])
		->where('language', Redaxscript\Registry::get('language'))->select('id')->findFlatArray();

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

			if ($accessValidator->validate($access, Redaxscript\Registry::get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}

				/* show if category or article matched */

				if ($category === $categoryId || $article === $articleId || (!$category && !$article))
				{
					/* parser */

					$parser = new Redaxscript\Parser(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
					$parser->init($text,
					[
						'route' => $route
					]);

					/* collect headline output */

					$output .= Redaxscript\Hook::trigger('extraFragmentStart', $r);
					if ($headline == 1)
					{
						$output .= '<h3 class="rs-title-extra" id="extra-' . $alias . '">' . $title . '</h3>';
					}

					/* collect box output */

					$output .= '<div class="rs-box-extra">' . $parser->getOutput() . '</div>' . Redaxscript\Hook::trigger('extraFragmentEnd', $r);

					/* prepend admin dock */

					if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token') && $firstParameter != 'logout')
					{
						$output .= admin_dock('extras', $id);
					}
				}
			}
		}
	}
	if (!$filter)
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

function infoline($table, $id, $author, $date)
{
	$output = Redaxscript\Hook::trigger('infolineStart');
	$time = date(Redaxscript\Db::getSetting('time'), strtotime($date));
	$date = date(Redaxscript\Db::getSetting('date'), strtotime($date));
	if ($table == 'articles')
	{
		$comments_total = Redaxscript\Db::forTablePrefix('comments')->where('article', $id)->count();
	}

	/* collect output */

	$output .= '<div class="rs-box-infoline rs-box-infoline-' . $table . '">';

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
		$output .= '<span class="rs-divider">' . Redaxscript\Db::getSetting('divider') . '</span><span class="rs-infoline-total">' . $comments_total . ' ';
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

function pagination($sub_active, $sub_maximum, $route)
{
	$output = Redaxscript\Hook::trigger('paginationStart');
	$output .= '<ul class="rs-list-pagination">';

	/* collect first and previous output */

	if ($sub_active > 1)
	{
		$first_route = $route;
		$previous_route = $route . '/' . ($sub_active - 1);
		$output .= '<li class="rs-item-first"><a href="' . Redaxscript\Registry::get('parameterRoute') . $first_route . '">' . Redaxscript\Language::get('first') . '</a></li>';
		$output .= '<li class="rs-item-previous"><a href="' . Redaxscript\Registry::get('parameterRoute') . $previous_route . '" rel="previous">' . Redaxscript\Language::get('previous') . '</a></li>';
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
			$output .= '<li class="rs-item-number"><a href="' . Redaxscript\Registry::get('parameterRoute') . $route . '/' . $i . '">' . $i . '</a></li>';
		}
	}

	/* collect next and last output */

	if ($sub_active < $sub_maximum)
	{
		$next_route = $route . '/' . ($sub_active + 1);
		$last_route = $route . '/' . $sub_maximum;
		$output .= '<li class="rs-item-next"><a href="' . Redaxscript\Registry::get('parameterRoute') . $next_route . '" rel="next">' . Redaxscript\Language::get('next') . '</a></li>';
		$output .= '<li class="rs-item-next"><a href="' . Redaxscript\Registry::get('parameterRoute') . $last_route . '">' . Redaxscript\Language::get('last') . '</a></li>';
	}
	$output .= '</ul>';
	$output .= Redaxscript\Hook::trigger('paginationEnd');
	echo $output;
}
<?php

/**
 * contents
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

function contents()
{
	$registry = Redaxscript\Registry::getInstance();
	$request = Redaxscript\Request::getInstance();
	$language = Redaxscript\Language::getInstance();
	$config = Redaxscript\Config::getInstance();
	$output = Redaxscript\Module\Hook::trigger('contentStart');
	$aliasValidator = new Redaxscript\Validator\Alias();
	$lastId = $registry->get('lastId');
	$lastTable = $registry->get('lastTable');
	$categoryId = $registry->get('categoryId');
	$articleId = $registry->get('articleId');
	$firstParameter = $registry->get('firstParameter');

	/* query articles */

	$articles = Redaxscript\Db::forTablePrefix('articles')->where('status', 1);
	$articles->whereLanguageIs($registry->get('language'));

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
		->where('language', $registry->get('language'))->select('id')->findFlatArray();

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
			$sub_active = $registry->get('lastSubParameter');

			/* sub parameter */

			if ($registry->get('lastSubParameter') > $sub_maximum || !$registry->get('lastSubParameter'))
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
		$error = $language->get('article_no');
	}
	else if (!$result || !$num_rows_active || $registry->get('contentError'))
	{
		$error = $language->get('content_not_found');
	}

	/* collect output */

	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		foreach ($result as $r)
		{
			$access = $r['access'];

			/* access granted */

			if ($accessValidator->validate($access, $registry->get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						if ($key !== 'language')
						{
							$$key = $value;
						}
					}
				}
				if ($lastTable == 'categories' || !$registry->get('fullRoute') || $aliasValidator->validate($firstParameter, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED)
				{
					$route = build_route('articles', $id);
				}

				/* parser */

				$parser = new Redaxscript\Content\Parser($registry, $request, $language, $config);
				$parser->process($text, $route);

				/* collect headline output */

				$output .= Redaxscript\Module\Hook::trigger('contentFragmentStart', $r);
				if ($headline == 1)
				{
					$output .= '<h2 class="rs-title-content" id="article-' . $alias . '">';
					if ($lastTable == 'categories' || !$registry->get('fullRoute') || $aliasValidator->validate($firstParameter, Redaxscript\Validator\Alias::MODE_DEFAULT) == Redaxscript\Validator\ValidatorInterface::PASSED
					)
					{
						$output .= '<a href="' . $registry->get('parameterRoute') . $route . '">' . $title . '</a>';
					}
					else
					{
						$output .= $title;
					}
					$output .= '</h2>';
				}

				/* collect box output */

				$output .= '<div class="rs-box-content">' . $parser->getOutput() . '</div>';
				if ($byline == 1)
				{
					$output .= byline('articles', $id, $author, $date);
				}
				$output .= Redaxscript\Module\Hook::trigger('contentFragmentEnd', $r);

				/* admin dock */

				if ($registry->get('loggedIn') == $registry->get('token') && $firstParameter != 'logout')
				{
					$output .= admin_dock('articles', $id);
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
				$error = $language->get('access_no');
			}
		}
		else if ($lastTable == 'articles' && $counter == 1)
		{
			$error = $language->get('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		/* show error */

		$messenger = new Redaxscript\Messenger($registry);
		echo $messenger->error($error, $language->get('something_wrong'));
	}
	else
	{
		$output .= Redaxscript\Module\Hook::trigger('contentEnd');
		echo $output;

		/* call comments as needed */

		if ($articleId)
		{
			/* comments replace */

			if ($comments == 1 && $registry->get('commentReplace'))
			{
				Redaxscript\Module\Hook::trigger('commentReplace');
			}

			/* else native comments */

			else if ($comments > 0)
			{
				$route = build_route('articles', $articleId);
				comments($articleId, $route);

				/* comment form */

				if ($comments == 1 || ($registry->get('commentNew') && $comments == 3))
				{
					$commentForm = new Redaxscript\View\CommentForm($registry, $language);
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
 * @category Content
 * @author Henry Ruhs
 *
 * @param string $filter
 */

function extras($filter)
{
	$registry = Redaxscript\Registry::getInstance();
	$request = Redaxscript\Request::getInstance();
	$language = Redaxscript\Language::getInstance();
	$config = Redaxscript\Config::getInstance();
	if (!$filter)
	{
		$output .= Redaxscript\Module\Hook::trigger('extraStart');
	}
	$categoryId = $registry->get('categoryId');
	$articleId = $registry->get('articleId');
	$firstParameter = $registry->get('firstParameter');

	/* query extras */

	$extras = Redaxscript\Db::forTablePrefix('extras')
		->whereLanguageIs($registry->get('language'));

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
		->where('language', $registry->get('language'))->select('id')->findFlatArray();

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

	if ($id_array)
	{
		$result = $extras->whereIn('id', $id_array)->findArray();
	}

	/* collect output */

	if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		foreach ($result as $r)
		{
			$access = $r['access'];

			/* access granted */

			if ($accessValidator->validate($access, $registry->get('myGroups')) === Redaxscript\Validator\ValidatorInterface::PASSED)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						if ($key !== 'language')
						{
							$$key = stripslashes($value);
						}
					}
				}

				/* show if category or article matched */

				if ($category === $categoryId || $article === $articleId || (!$category && !$article))
				{
					/* parser */

					$parser = new Redaxscript\Content\Parser($registry, $request, $language, $config);
					$parser->process($text, $route);

					/* collect headline output */

					$output .= Redaxscript\Module\Hook::trigger('extraFragmentStart', $r);
					if ($headline == 1)
					{
						$output .= '<h3 class="rs-title-extra" id="extra-' . $alias . '">' . $title . '</h3>';
					}

					/* collect box output */

					$output .= '<div class="rs-box-extra">' . $parser->getOutput() . '</div>' . Redaxscript\Module\Hook::trigger('extraFragmentEnd', $r);

					/* prepend admin dock */

					if ($registry->get('loggedIn') == $registry->get('token') && $firstParameter != 'logout')
					{
						$output .= admin_dock('extras', $id);
					}
				}
			}
		}
	}
	if (!$filter)
	{
		$output .= Redaxscript\Module\Hook::trigger('extraEnd');
	}
	echo $output;
}

/**
 * byline
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 *
 * @param string $table
 * @param integer $id
 * @param string $author
 * @param string $date
 *
 * @return string
 */

function byline($table, $id, $author, $date)
{
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('bylineStart');
	$time = date(Redaxscript\Db::getSetting('time'), strtotime($date));
	$date = date(Redaxscript\Db::getSetting('date'), strtotime($date));
	if ($table == 'articles')
	{
		$comments_total = Redaxscript\Db::forTablePrefix('comments')->where('article', $id)->count();
	}

	/* collect output */

	$output .= '<div class="rs-box-byline">';

	/* collect author output */

	if ($table == 'articles')
	{
		$output .= '<span class="rs-text-by">' . $language->get('posted_by') . ' ' . $author . '</span>';
		$output .= '<span class="rs-text-on"> ' . $language->get('on') . ' </span>';
	}

	/* collect date and time output */

	$output .= '<span class="rs-text-date">' . $date . '</span>';
	$output .= '<span class="rs-text-at"> ' . $language->get('at') . ' </span>';
	$output .= '<span class="rs-text-time">' . $time . '</span>';

	/* collect comment output */

	if ($comments_total)
	{
		$output .= '<span class="rs-text-divider">' . Redaxscript\Db::getSetting('divider') . '</span><span class="rs-text-total">' . $comments_total . ' ';
		if ($comments_total == 1)
		{
			$output .= $language->get('comment');
		}
		else
		{
			$output .= $language->get('comments');
		}
		$output .= '</span>';
	}
	$output .= '</div>';
	$output .= Redaxscript\Module\Hook::trigger('bylineEnd');
	return $output;
}

/**
 * pagination
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 *
 * @param integer $sub_active
 * @param integer $sub_maximum
 * @param string $route
 */

function pagination($sub_active, $sub_maximum, $route)
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('paginationStart');
	$output .= '<ul class="rs-list-pagination">';

	/* collect first and previous output */

	if ($sub_active > 1)
	{
		$first_route = $route;
		$previous_route = $route . '/' . ($sub_active - 1);
		$output .= '<li class="rs-item-first"><a href="' . $registry->get('parameterRoute') . $first_route . '">' . $language->get('first') . '</a></li>';
		$output .= '<li class="rs-item-previous"><a href="' . $registry->get('parameterRoute') . $previous_route . '" rel="prev">' . $language->get('previous') . '</a></li>';
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
			$output .= '<li class="rs-item-number"><a href="' . $registry->get('parameterRoute') . $route . '/' . $i . '">' . $i . '</a></li>';
		}
	}

	/* collect next and last output */

	if ($sub_active < $sub_maximum)
	{
		$next_route = $route . '/' . ($sub_active + 1);
		$last_route = $route . '/' . $sub_maximum;
		$output .= '<li class="rs-item-next"><a href="' . $registry->get('parameterRoute') . $next_route . '" rel="next">' . $language->get('next') . '</a></li>';
		$output .= '<li class="rs-item-last"><a href="' . $registry->get('parameterRoute') . $last_route . '">' . $language->get('last') . '</a></li>';
	}
	$output .= '</ul>';
	$output .= Redaxscript\Module\Hook::trigger('paginationEnd');
	echo $output;
}
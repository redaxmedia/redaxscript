<?php

/**
 * comments
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Comments
 * @author Henry Ruhs
 *
 * @param integer $article
 * @param string $route
 */

function comments($article, $route)
{
	$registry = Redaxscript\Registry::getInstance();
	$language = Redaxscript\Language::getInstance();
	$output = Redaxscript\Module\Hook::trigger('commentStart');

	/* query comments */

	$comments = Redaxscript\Db::forTablePrefix('comments')
		->where(
		[
			'status' => 1,
			'article' => $article
		])
		->whereLanguageIs($registry->get('language'))
		->orderGlobal('rank');

	/* query result */

	$result = $comments->findArray();
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
	$comments->limit($offset_string . Redaxscript\Db::getSetting('limit'));

	/* query result */

	$result = $comments->findArray();
	$num_rows_active = count($result);

	/* handle error */

	if (!$result || !$num_rows)
	{
		$error = $language->get('comment_no');
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
							$$key = stripslashes($value);
						}
					}
				}

				/* collect headline output */

				$output .= Redaxscript\Module\Hook::trigger('commentFragmentStart', $r) . '<h3 id="comment-' . $id . '" class="rs-title-comment">';
				if ($url)
				{
					$output .= '<a href="' . $url . '" rel="nofollow">' . $author . '</a>';
				}
				else
				{
					$output .= $author;
				}
				$output .= '</h3>';

				/* collect box output */

				$output .= '<div class="rs-box-comment">' . $text . '</div>';
				$output .= byline('comments', $id, $author, $date);
				$output .= Redaxscript\Module\Hook::trigger('commentFragmentEnd', $r);

				/* admin dock */

				if ($registry->get('loggedIn') == $registry->get('token') && $registry->get('firstParameter') != 'logout')
				{
					$output .= admin_dock('comments', $id);
				}
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if ($num_rows_active == $counter)
		{
			$error = $language->get('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="rs-box-comment">' . $error . $language->get('point') . '</div>';
	}
	$output .= Redaxscript\Module\Hook::trigger('commentEnd');
	echo $output;

	/* call pagination as needed */

	if ($sub_maximum > 1 && Redaxscript\Db::getSetting('pagination') == 1)
	{
		pagination($sub_active, $sub_maximum, $route);
	}
}
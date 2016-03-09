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

function comments($article = '', $route = '')
{
	$output = Redaxscript\Hook::trigger('commentStart');

	/* query comments */

	$comments = Redaxscript\Db::forTablePrefix('comments')
		->where(array(
			'status' => 1,
			'article' => $article
		))
		->whereRaw('(language = ? OR language is ?)', array(
				Redaxscript\Registry::get('language'),
				null
		))
		->orderGlobal('rank');

	/* query result */

	$result = $comments->findArray();
	if ($result)
	{
		$num_rows = count($result);
		$sub_maximum = ceil($num_rows / Redaxscript\Db::getSettings('limit'));
		$sub_active = LAST_SUB_PARAMETER;

		/* sub parameter */

		if (LAST_SUB_PARAMETER > $sub_maximum || LAST_SUB_PARAMETER == '')
		{
			$sub_active = 1;
		}
		else
		{
			$offset_string = ($sub_active - 1) * Redaxscript\Db::getSettings('limit') . ', ';
		}
	}
	$comments->limit($offset_string . Redaxscript\Db::getSettings('limit'));

	/* query result */

	$result = $comments->findArray();
	$num_rows_active = count($result);

	/* handle error */

	if ($result == '' || $num_rows == '')
	{
		$error = Redaxscript\Language::get('comment_no');
	}

	/* collect output */

	else if ($result)
	{
		$accessValidator = new Redaxscript\Validator\Access();
		$output .= '<div class="rs-box-line"></div>';
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

				/* collect headline output */

				$output .= Redaxscript\Hook::trigger('commentFragmentStart', $r) . '<h3 id="comment-' . $id . '" class="rs-title-comment">';
				if ($url)
				{
					$output .= '<a href="' . $url . '" class="rs-link-default" rel="nofollow">' . $author . '</a>';
				}
				else
				{
					$output .= $author;
				}
				$output .= '</h3>';

				/* collect box output */

				$output .= infoline('comments', $id, $author, $date);
				$output .= '<div class="rs-box-comment">' . $text . '</div>' . Redaxscript\Hook::trigger('commentFragmentEnd', $r);

				/* admin dock */

				if (LOGGED_IN == TOKEN && FIRST_PARAMETER != 'logout')
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
			$error = Redaxscript\Language::get('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="rs-box-comment-error">' . $error . Redaxscript\Language::get('point') . '</div>';
	}
	$output .= Redaxscript\Hook::trigger('commentEnd');
	echo $output;

	/* call pagination as needed */

	if ($sub_maximum > 1 && Redaxscript\Db::getSettings('pagination') == 1)
	{
		pagination($sub_active, $sub_maximum, $route);
	}
}

/**
 * comment post
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Comments
 * @author Henry Ruhs
 */

function comment_post()
{
	$specialFilter = new Redaxscript\Filter\Special();
	$emailFilter = new Redaxscript\Filter\Email();
	$urlFilter = new Redaxscript\Filter\Url();
	$htmlFilter = new Redaxscript\Filter\Html();
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();
	$urlValidator = new Redaxscript\Validator\Url();

	/* clean post */

	$article = $r['article'] = $specialFilter->sanitize($_POST['article']);
	$author = $r['author'] = $specialFilter->sanitize($_POST['author']);
	$email = $r['email'] = $emailFilter->sanitize($_POST['email']);
	$url = $r['url'] = $urlFilter->sanitize($_POST['url']);
	$text = nl2br($_POST['text']);
	$text = $r['text'] = $htmlFilter->sanitize($text);
	$r['language'] = Redaxscript\Db::forTablePrefix('articles')->whereIdIs($article)->language;
	$r['date'] = $_POST['date'];
	$r['rank'] = Redaxscript\Db::forTablePrefix('comments')->max('rank') + 1;
	$r['access'] = Redaxscript\Db::forTablePrefix('articles')->whereIdIs($article)->access;
	$r['date'] = Redaxscript\Registry::get('now');
	if ($r['access'] == '')
	{
		$r['access'] = null;
	}
	$task = $_POST['task'];
	$solution = $_POST['solution'];
	$route = build_route('articles', $article);

	/* validate post */

	if ($author == '')
	{
		$error = Redaxscript\Language::get('author_empty');
	}
	else if ($email == '')
	{
		$error = Redaxscript\Language::get('email_empty');
	}
	else if ($text == '')
	{
		$error = Redaxscript\Language::get('comment_empty');
	}
	else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('email_incorrect');
	}
	else if ($url && $urlValidator->validate($url) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('url_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = Redaxscript\Language::get('captcha_incorrect');
	}
	else
	{
		if (COMMENTS_NEW == 0 && Redaxscript\Db::getSettings('moderation') == 1)
		{
			$r['status'] = 0;
			$success = Redaxscript\Language::get('comment_moderation');
		}
		else
		{
			$r['status'] = 1;
			$success = Redaxscript\Language::get('comment_sent');
		}

		/* send comment notification */

		if (Redaxscript\Db::getSettings('notification') == 1)
		{
			/* prepare body parts */

			$emailLink = '<a href="mailto:' . $email . '">' . $email . '</a>';
			if ($url)
			{
				$urlLink = '<a href="' . $url . '">' . $url . '</a>';
			}
			$articleRoute = ROOT . '/' . REWRITE_ROUTE . $route;
			$articleLink = '<a href="' . $articleRoute . '">' . $articleRoute . '</a>';

			/* prepare mail inputs */

			$toArray = array(
				s('author') => Redaxscript\Db::getSettings('email')
			);
			$fromArray = array(
				$author => $email
			);
			$subject = Redaxscript\Language::get('comment_new');
			$bodyArray = array(
				'<strong>' . Redaxscript\Language::get('author') . Redaxscript\Language::get('colon') . '</strong> ' . $author,
				'<br />',
				'<strong>' . Redaxscript\Language::get('email') . Redaxscript\Language::get('colon') . '</strong> ' . $emailLink,
				'<br />',
				'<strong>' . Redaxscript\Language::get('url') . Redaxscript\Language::get('colon') . '</strong> ' . $urlLink,
				'<br />',
				'<strong>' . Redaxscript\Language::get('article') . Redaxscript\Language::get('colon') . '</strong> ' . $articleLink,
				'<br />',
				'<br />',
				'<strong>' . Redaxscript\Language::get('comment') . Redaxscript\Language::get('colon') . '</strong> ' . $text
			);

			/* mailer object */

			$mailer = new Redaxscript\Mailer();
			$mailer->init($toArray, $fromArray, $subject, $bodyArray);
			$mailer->send();
		}

		/* create comment */

		Redaxscript\Db::forTablePrefix('comments')
			->create()
			->set($r)
			->save();
	}

	/* handle error */

	$messenger = new Redaxscript\Messenger();
	if ($error)
	{
		echo $messenger->setAction(Redaxscript\Language::get('back'), $route)->error($error, Redaxscript\Language::get('error_occurred'));
	}

	/* handle success */

	else
	{
		echo $messenger->setAction(Redaxscript\Language::get('continue'), $route)->doRedirect()->success($success, Redaxscript\Language::get('operation_completed'));
	}
}

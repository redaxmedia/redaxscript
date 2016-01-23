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
	$comments->limit($offset_string . s('limit'));

	/* query result */

	$result = $comments->findArray();
	$num_rows_active = count($result);

	/* handle error */

	if ($result == '' || $num_rows == '')
	{
		$error = l('comment_no');
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
					$output .= anchor_element('external', '', '', $author, $url, '', 'rel="nofollow"');
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
			$error = l('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="rs-box-comment-error">' . $error . l('point') . '</div>';
	}
	$output .= Redaxscript\Hook::trigger('commentEnd');
	echo $output;

	/* call pagination as needed */

	if ($sub_maximum > 1 && s('pagination') == 1)
	{
		pagination($sub_active, $sub_maximum, $route);
	}
}

/**
 * comment form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Comments
 * @author Henry Ruhs
 *
 * @param integer $article
 * @param string $language
 */

function comment_form($article = '', $language = '')
{
	$output = Redaxscript\Hook::trigger('commentFormStart');

	/* html elements */

	$titleElement = new Redaxscript\Html\Element();
	$titleElement->init('h2', array(
			'class' => 'rs-title-content',
	));
	$titleElement->text(Redaxscript\Language::get('comment_new'));
	$formElement = new Redaxscript\Html\Form(Redaxscript\Registry::getInstance(), Redaxscript\Language::getInstance());
	$formElement->init(array(
		'textarea' => array(
			'class' => 'rs-js-auto-resize rs-js-editor-textarea rs-field-textarea'
		),
		'button' => array(
			'submit' => array(
				'name' => 'comment_post'
			)
		)
	), array(
		'captcha' => Redaxscript\Db::getSettings('captcha') > 0
	));

	/* create the form */

	$formElement
		->append('<fieldset>')
		->legend()
		->append('<ul><li>')
		->label('* ' . Redaxscript\Language::get('author'), array(
			'for' => 'author'
		))
		->text(array(
			'id' => 'author',
			'name' => 'author',
			'readonly' => Redaxscript\Registry::get('myName') ? 'readonly' : null,
			'required' => 'required',
			'value' => Redaxscript\Registry::get('myName')
		))
		->append('</li><li>')
		->label('* ' . Redaxscript\Language::get('email'), array(
			'for' => 'email'
		))
		->email(array(
			'id' => 'email',
			'name' => 'email',
			'readonly' => Redaxscript\Registry::get('myEmail') ? 'readonly' : null,
			'required' => 'required',
			'value' => Redaxscript\Registry::get('myEmail')
		))
		->append('</li><li>')
		->label(Redaxscript\Language::get('url'), array(
			'for' => 'url'
		))
		->url(array(
			'id' => 'url',
			'name' => 'url'
		))
		->append('</li><li>')
		->label('* ' . Redaxscript\Language::get('message'), array(
			'for' => 'text'
		))
		->textarea(array(
			'id' => 'text',
			'name' => 'text',
			'required' => 'required'
		))
		->append('</li>');
	if (Redaxscript\Db::getSettings('captcha') > 0)
	{
		$formElement
			->append('<li>')
			->captcha('task')
			->append('</li>');
	}
	$formElement->append('</ul></fieldset>');
	if (Redaxscript\Db::getSettings('captcha') > 0)
	{
		$formElement->captcha('solution');
	}
	$formElement
		->hidden(array(
			'name' => 'article',
			'value' => $article
		))
		->hidden(array(
			'name' => 'language',
			'value' => $language
		))
		->token()
		->submit()
		->reset();

	/* collect output */

	$output .= $titleElement . $formElement;
	$output .= Redaxscript\Hook::trigger('commentFormEnd');
	echo $output;
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
	$emailValidator = new Redaxscript\Validator\Email();
	$captchaValidator = new Redaxscript\Validator\Captcha();
	$urlValidator = new Redaxscript\Validator\Url();

	/* clean post */

	$author = $r['author'] = clean($_POST['author'], 0);
	$email = $r['email'] = clean($_POST['email'], 3);
	$url = $r['url'] = clean($_POST['url'], 4);
	$text = nl2br($_POST['text']);
	$text = $r['text'] = clean($text, 1);
	$r['language'] = clean($_POST['language'], 0);
	$r['date'] = clean($_POST['date'], 5);
	$article = $r['article'] = clean($_POST['article'], 0);
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
		$error = l('author_empty');
	}
	else if ($email == '')
	{
		$error = l('email_empty');
	}
	else if ($text == '')
	{
		$error = l('comment_empty');
	}
	else if ($emailValidator->validate($email) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('email_incorrect');
	}
	else if ($url && $urlValidator->validate($url) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('url_incorrect');
	}
	else if ($captchaValidator->validate($task, $solution) == Redaxscript\Validator\ValidatorInterface::FAILED)
	{
		$error = l('captcha_incorrect');
	}
	else
	{
		if (COMMENTS_NEW == 0 && s('moderation') == 1)
		{
			$r['status'] = 0;
			$success = l('comment_moderation');
		}
		else
		{
			$r['status'] = 1;
			$success = l('comment_sent');
		}

		/* send comment notification */

		if (s('notification') == 1)
		{
			/* prepare body parts */

			$emailLink = anchor_element('email', '', '', $email);
			if ($url)
			{
				$urlLink = anchor_element('external', '', '', $url);
			}
			$articleRoute = ROOT . '/' . REWRITE_ROUTE . $route;
			$articleLink = anchor_element('external', '', '', $articleRoute, $articleRoute);

			/* prepare mail inputs */

			$toArray = array(
				s('author') => s('email')
			);
			$fromArray = array(
				$author => $email
			);
			$subject = l('comment_new');
			$bodyArray = array(
				'<strong>' . l('author') . l('colon') . '</strong> ' . $author,
				'<br />',
				'<strong>' . l('email') . l('colon') . '</strong> ' . $emailLink,
				'<br />',
				'<strong>' . l('url') . l('colon') . '</strong> ' . $urlLink,
				'<br />',
				'<strong>' . l('article') . l('colon') . '</strong> ' . $articleLink,
				'<br />',
				'<br />',
				'<strong>' . l('comment') . l('colon') . '</strong> ' . $text
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

	if ($error)
	{
		notification(l('error_occurred'), $error, l('back'), $route);
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), $success, l('continue'), $route);
	}
}

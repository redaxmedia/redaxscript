<?php

/**
 * comments
 *
 * @param integer $article
 * @param string $route
 */

function comments($article = '', $route = '')
{
	hook(__FUNCTION__ . '_start');

	/* query comments */

	$query = 'SELECT id, author, url, text, date, article, access FROM ' . PREFIX . 'comments WHERE (language = \'' . LANGUAGE . '\' || language = \'\') && article = ' . $article . ' && status = 1 ORDER BY rank ' . s('order');
	$result = mysql_query($query);
	if ($result)
	{
		$num_rows = mysql_num_rows($result);
		$sub_maximum = ceil($num_rows / s('limit'));
		$sub_active = LAST_SUB_PARAMETER;

		/* if sub parameter */

		if (LAST_SUB_PARAMETER > $sub_maximum || LAST_SUB_PARAMETER == '')
		{
			$sub_active = 1;
		}
		else
		{
			$offset_string = ($sub_active - 1) * s('limit') . ', ';
		}
	}
	$query .= ' LIMIT ' . $offset_string . s('limit');
	$result = mysql_query($query);
	$num_rows_active = mysql_num_rows($result);

	/* handle error */

	if ($result == '' || $num_rows == '')
	{
		$error = l('comment_no');
	}

	/* collect output */

	else if ($result)
	{
		$output = '<div class="box_line"></div>';
		while ($r = mysql_fetch_assoc($result))
		{
			$access = $r['access'];
			$check_access = check_access($access, MY_GROUPS);

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

				/* collect headline output */

				$output .= hook('comment_start') . '<h3 id="comment-' . $id . '" class="title_comment">';
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
				$output .= '<div class="box_comment">' . $text . '</div>' . hook('comment_end');

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
		$output = '<div class="box_comment_error">' . $error . l('point') . '</div>';
	}
	echo $output;

	/* call pagination as needed */

	if ($sub_maximum > 1 && s('pagination') == 1)
	{
		pagination($sub_active, $sub_maximum, $route);
	}
	hook(__FUNCTION__ . '_end');
}

/**
 * comment form
 *
 * @param integer $article
 * @param string $language
 * @param string $access
 */

function comment_form($article = '', $language = '', $access = '')
{
	hook(__FUNCTION__ . '_start');

	/* disable fields if attack blocked */

	if (ATTACK_BLOCKED > 9)
	{
		$class_readonly = $class_disabled = ' field_disabled';
		$code_readonly = $code_disabled = ' disabled="disabled"';
	}

	/* define fields if logged in */

	else if (LOGGED_IN == TOKEN)
	{
		$author = MY_USER;
		$email = MY_EMAIL;
		$class_readonly = ' field_readonly';
		$code_readonly = ' readonly="readonly"';
	}

	/* captcha object */

	if (s('captcha') > 0)
	{
		$captcha = new Redaxscript_Captcha();
	}

	/* collect output */

	$output = '<h2 class="title_content">' . l('comment_new') . '</h2>';
	$output .= form_element('form', 'form_comment', 'js_check_required form_default form_comment', '', '', '', 'method="post"');
	$output .= form_element('fieldset', '', 'set_comment', '', '', l('fields_required') . l('point')) . '<ul>';
	$output .= '<li>' . form_element('text', 'author', 'js_required field_text field_note' . $class_readonly, 'author', $author, '* ' . l('author'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
	$output .= '<li>' . form_element('email', 'email', 'js_required field_text field_note' . $class_readonly, 'email', $email, '* ' . l('email'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
	$output .= '<li>' . form_element('url', 'url', 'field_text' . $class_disabled, 'url', '', l('url'), 'maxlength="50"' . $code_disabled) . '</li>';
	$output .= '<li>' . form_element('textarea', 'text', 'js_required js_auto_resize js_editor field_textarea field_note' . $class_disabled, 'text', '', '* ' . l('comment'), 'rows="5" cols="100" required="required"' . $code_disabled) . '</li>';

	/* collect captcha task output */

	if (LOGGED_IN != TOKEN && s('captcha') > 0)
	{
		$output .= '<li>' . form_element('number', 'task', 'js_required field_text field_note' . $class_disabled, 'task', '', $captcha->getTask(), 'maxlength="2" required="required"' . $code_disabled) . '</li>';
	}
	$output .= '</ul></fieldset>';

	/* collect hidden output */

	$output .= form_element('hidden', '', '', 'language', $language);
	$output .= form_element('hidden', '', '', 'date', NOW);
	$output .= form_element('hidden', '', '', 'article', $article);
	$output .= form_element('hidden', '', '', 'access', $access);

	/* collect captcha solution output */

	if (s('captcha') > 0)
	{
		if (LOGGED_IN == TOKEN)
		{
			$output .= form_element('hidden', '', '', 'task', $captcha->getSolution());
		}
		$output .= form_element('hidden', '', '', 'solution', $captcha->getSolution());
	}

	/* collect hidden and button output */

	$output .= form_element('hidden', '', '', 'token', TOKEN);
	$output .= form_element('button', '', 'js_submit field_button' . $class_disabled, 'comment_post', l('create'), '', $code_disabled);
	$output .= '</form>';
	$_SESSION[ROOT . '/comment'] = 'visited';
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/**
 * comment post
 */

function comment_post()
{
	/* clean post */

	if (ATTACK_BLOCKED < 10 && $_SESSION[ROOT . '/comment'] == 'visited')
	{
		$author = $r['author'] = clean($_POST['author'], 0);
		$email = $r['email'] = clean($_POST['email'], 3);
		$url = $r['url'] = clean($_POST['url'], 4);
		$text = break_up($_POST['text']);
		$text = $r['text'] = clean($text, 1);
		$r['language'] = clean($_POST['language'], 0);
		$r['date'] = clean($_POST['date'], 1);
		$article = $r['article'] = clean($_POST['article'], 0);
		$r['rank'] = query_plumb('rank', 'comments', 'max') + 1;
		$r['access'] = clean($_POST['access'], 0);
		if ($r['access'] == '')
		{
			$r['access'] = 0;
		}
		$task = $_POST['task'];
		$solution = $_POST['solution'];
		$route = build_route('articles', $article);
	}

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
	else if (check_email($email) == 0)
	{
		$error = l('email_incorrect');
	}
	else if ($url && check_url($url) == 0)
	{
		$error = l('url_incorrect');
	}
	else if (check_captcha($task, $solution) == 0)
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
			$articleLink = anchor_element('', '', '', $articleRoute);

			/* prepare mail inputs */

			$toArray = array(
				s('author') => s('email')
			);
			$fromArray = array(
				$author => $email
			);
			$subject = l('comment_new');
			$bodyArray = array(
				l('author') => $author . ' (' . MY_IP . ')',
				l('email') => $emailLink,
				l('url') => $urlLink,
				'<br />',
				l('comment') => $text,
				'<br />',
				l('article') => $articleLink
			);

			/* mail object */

			$mail = new Redaxscript_Mail($toArray, $fromArray, $subject, $bodyArray);
			$mail->send();
		}

		/* build key and value strings */

		$last = end(array_keys($r));
		foreach ($r as $key => $value)
		{
			$key_string .= $key;
			$value_string .= '\'' . $value . '\'';
			if ($last != $key)
			{
				$key_string .= ', ';
				$value_string .= ', ';
			}
		}

		/* insert comment */

		$query = 'INSERT INTO ' . PREFIX . 'comments (' . $key_string . ') VALUES (' . $value_string . ')';
		mysql_query($query);
	}

	/* handle error */

	if ($error)
	{
		if (s('blocker') == 1)
		{
			$_SESSION[ROOT . '/attack_blocked']++;
		}
		notification(l('error_occurred'), $error, l('back'), $route);
	}

	/* handle success */

	else
	{
		notification(l('operation_completed'), $success, l('continue'), $route);
	}
	$_SESSION[ROOT . '/comment'] = '';
}
?>
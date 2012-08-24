<?php

/* language detection */

function language_detection()
{
	/* get language */

	if ($_GET['l'])
	{
		$language = clean($_GET['l'], 1);
		if (file_exists('languages/' . $language . '.php'))
		{
			$_SESSION[ROOT . '/language_selected'] = 1;
		}
	}

	/* else use language from session */

	else
	{
		$language = $_SESSION[ROOT . '/language'];
	}

	/* if language not selected */

	if ($_SESSION[ROOT . '/language_selected'] == '')
	{
		/* query site language */

		if (s('language') != 'detect')
		{
			$language = s('language');
		}

		/* else use browser language */

		else
		{
			$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}
	}

	/* setup language session */

	if (file_exists('languages/' . $language . '.php'))
	{
		$_SESSION[ROOT . '/language'] = $language;
	}
	else
	{
		$_SESSION[ROOT . '/language'] = 'en';
	}
}

/* template detection */

function template_detection()
{
	/* get template */

	if ($_GET['t'])
	{
		$template = clean($_GET['t'], 1);
		if (file_exists('templates/' . $template . '/index.phtml'))
		{
			$_SESSION[ROOT . '/template_selected'] = 1;
		}
	}

	/* else use template from session */

	else
	{
		$template = $_SESSION[ROOT . '/template'];
	}

	/* if template not selected */

	if ($_SESSION[ROOT . '/template_selected'] == '')
	{
		/* query site template */

		if (s('template'))
		{
			$template = s('template');
		}

		/* retrieve template from content */

		if (LAST_TABLE)
		{
			$last_template = retrieve('template', LAST_TABLE, 'alias', LAST_PARAMETER);
			if ($last_template)
			{
				$template = $last_template;
			}
		}

		/* else use homepage template */

		else if (FULL_STRING == '' || (LOGGED_IN == TOKEN && FULL_STRING == 'admin') && s('homepage') > 0)
		{
			$homepage_template = retrieve('template', 'articles', 'id', s('homepage'));
			if ($homepage_template)
			{
				$template = $homepage_template;
			}
		}
	}

	/* setup template session */

	if (file_exists('templates/' . $template . '/index.phtml'))
	{
		$_SESSION[ROOT . '/template'] = $template;
	}
	else
	{
		$_SESSION[ROOT . '/template'] = 'default';
	}
}
?>
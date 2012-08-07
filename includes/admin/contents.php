<?php

/* admin contents list */

function admin_contents_list()
{
	hook(__FUNCTION__ . '_start');

	/* setup permission variables */

	$new = constant(strtoupper(TABLE_PARAMETER) . '_NEW');
	if (TABLE_PARAMETER == 'comments')
	{
		$articles_total = query_total('articles');
		$articles_comments_disable = query_total('articles', 'comments', 0);
		if ($articles_total == $articles_comments_disable)
		{
			$new = 0;
		}
	}
	$edit = constant(strtoupper(TABLE_PARAMETER) . '_EDIT');
	$delete = constant(strtoupper(TABLE_PARAMETER) . '_DELETE');

	/* switch table */

	switch (TABLE_PARAMETER)
	{
		case 'categories':
			$single = 'category';
			break;
		case 'articles':
			$single = 'article';
			break;
		case 'extras':
			$single = 'extra';
			break;
		case 'comments':
			$single = 'comment';
			break;
	}

	/* query contents */

	$query = 'SELECT * FROM ' . PREFIX . TABLE_PARAMETER . ' ORDER BY rank ASC';
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);

	/* collect listing output */

	$output = '<h2 class="title_content">' . l(TABLE_PARAMETER) . '</h2>';
	if ($new == 1)
	{
		$output .= '<a class="field_button_admin field_button_plus" href="' . REWRITE_STRING . 'admin/new/' . TABLE_PARAMETER . '"><span><span>' . l($single . '_new') . '</span></span></a>';
	}
	$output .= '<div class="wrapper_table_admin"><table class="table table_admin">';
	$output .= '<thead><tr><th class="s3o4 column_first">' . l('title') . '</th><th class="s1o4 column_second">';
	if (TABLE_PARAMETER == 'comments')
	{
		$output .= l('identifier');
	}
	else
	{
		$output .= l('alias');
	}
	$output .= '</th><th class="column_move column_last">' . l('rank') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td class="column_first">' . l('title') . '</td><td class="column_second">';
	if (TABLE_PARAMETER == 'comments')
	{
		$output .= l('identifier');
	}
	else
	{
		$output .= l('alias');
	}
	$output .= '</td><td class="column_move column_last">' . l('rank') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l($single . '_no') . l('point');
	}
	else if ($result)
	{
		$output .= '<tbody>';
		while ($r = mysql_fetch_assoc($result))
		{
			$access = $r['access'];
			$check_access = check_access($access, MY_GROUPS);
			if ($check_access == 1)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						$$key = stripslashes($value);
					}
				}
				if (TABLE_PARAMETER == 'comments')
				{
					$name = $author;
				}
				else
				{
					$name = $title;
				}
				if ($status == 1)
				{
					$class_status = '';
				}
				else
				{
					$class_status = 'row_disabled';
				}

				/* build string */

				if (TABLE_PARAMETER != 'extras' && $status == 1)
				{
					if (TABLE_PARAMETER == 'categories' && $parent == 0 || TABLE_PARAMETER == 'articles' && $category == 0)
					{
						$string = $alias;
					}
					else
					{
						$string = build_string(TABLE_PARAMETER, $id);
					}
				}
				else
				{
					$string = '';
				}

				/* collect table row */

				$output .= '<tr';
				if ($class_status)
				{
					$output .= ' class="' . $class_status . '"';
				}
				$output .= '><td class="column_first">';
				if ($language)
				{
					$output .= '<span class="icon_flag language_' . $language . '" title="' . l($language) . '">' . $language . '</span>';
				}
				if ($status == 1)
				{
					$output .= anchor_element('internal', '', 'link_view', $name, $string);
				}
				else
				{
					$output .= $name;
				}

				/* collect control output */

				if ($edit == 1 || $delete == 1)
				{
					$output .= '<ul class="list_control_admin">';
				}
				if ($status == 2)
				{
					$output .= '<li class="item_future_posting"><span class="future_posting">' . l('future_posting') . '</span></li>';
				}
				if ($edit == 1)
				{
					if ($status == 1)
					{
						$output .= '<li class="item_unpublish">' . anchor_element('internal', '', '', l('unpublish'), 'admin/unpublish/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN) . '</li>';
					}
					else if ($status == 0 || $date < NOW)
					{
						$output .= '<li class="item_publish">' . anchor_element('internal', '', '', l('publish'), 'admin/publish/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN) . '</li>';
					}
					$output .= '<li class="item_edit">' . anchor_element('internal', '', '', l('edit'), 'admin/edit/' . TABLE_PARAMETER . '/' . $id) . '</li>';
				}
				if ($delete == 1)
				{
					$output .= '<li class="item_delete">' . anchor_element('internal', '', 'js_confirm', l('delete'), 'admin/delete/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN) . '</li>';
				}
				if ($edit == 1 || $delete == 1)
				{
					$output .= '</ul>';
				}

				/* collect premature output */

				$output .= '</td><td class="column_second">';
				if (TABLE_PARAMETER == 'comments')
				{
					$output .= $id;
				}
				else
				{
					$output .= $alias;
				}
				$output .= '</td><td class="column_move column_last">';

				/* collect control output */

				if ($edit == 1)
				{
					$rank_desc = query_plumb('rank', TABLE_PARAMETER, 'max');
					if ($rank > 1)
					{
						$output .= anchor_element('internal', '', 'move_up', l('up'), 'admin/up/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN);
					}
					else
					{
						$output .= '<span class="move_up">' . l('up') . '</span>';
					}
					if ($rank < $rank_desc)
					{
						$output .= anchor_element('internal', '', 'move_down', l('down'), 'admin/down/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN);
					}
					else
					{
						$output .= '<span class="move_down">' . l('down') . '</span>';
					}
					$output .= '</td>';
				}
				$output .= '</tr>';
			}
			else
			{
				$counter++;
			}
		}
		$output .= '</tbody>';

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = l('access_no') . l('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/* admin contents form */

function admin_contents_form()
{
	hook(__FUNCTION__ . '_start');

	/* setup permission variables */

	$new = constant(strtoupper(TABLE_PARAMETER) . '_NEW');
	$edit = constant(strtoupper(TABLE_PARAMETER) . '_EDIT');
	$delete = constant(strtoupper(TABLE_PARAMETER) . '_DELETE');

	/* switch table */

	switch (TABLE_PARAMETER)
	{
		case 'categories':
			$single = 'category';
			break;
		case 'articles':
			$single = 'article';
			break;
		case 'extras':
			$single = 'extra';
			break;
		case 'comments':
			$single = 'comment';
			break;
	}

	/* define fields for existing user */

	if (ADMIN_PARAMETER == 'edit' && ID_PARAMETER)
	{
		/* query content */

		$query = 'SELECT * FROM ' . PREFIX . TABLE_PARAMETER . ' WHERE id = ' . ID_PARAMETER;
		$result = mysql_query($query);
		$r = mysql_fetch_assoc($result);
		if ($r)
		{
			foreach ($r as $key => $value)
			{
				$$key = stripslashes($value);
			}
		}
		if (TABLE_PARAMETER == 'comments')
		{
			$wording_headline = $author;
		}
		else
		{
			$wording_headline = $title;
		}
		if (TABLE_PARAMETER != 'categories')
		{
			$text = htmlspecialchars($text);
		}
		$wording_submit = l('save');
		$string = 'admin/process/' . TABLE_PARAMETER . '/' . $id;
	}

	/* else define fields for new content */

	else if (ADMIN_PARAMETER == 'new')
	{
		if (TABLE_PARAMETER == 'comments')
		{
			$author = MY_USER;
			$email = MY_EMAIL;
			$class_readonly = ' field_readonly';
			$code_readonly = ' readonly="readonly"';
		}
		if (TABLE_PARAMETER == 'categories')
		{
			$parent = 0;
		}
		if (TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras')
		{
			$category = 0;
			$headline = 1;
		}
		if (TABLE_PARAMETER == 'articles')
		{
			$infoline = 0;
			$comments = 0;
		}
		$status = 1;
		$rank = query_plumb('rank', TABLE_PARAMETER, 'max') + 1;
		$access = 0;
		$wording_headline = l($single . '_new');
		$wording_submit = l('create');
		$string = 'admin/process/' . TABLE_PARAMETER;
	}

	/* collect output */

	$output = '<h2 class="title_content">' . $wording_headline . '</h2>';

	/* collect tab output */

	$output .= '<ul class="js_list_tab list_tab list_tab_admin">';
	$output .= '<li class="js_item_active item_active item_first">' . anchor_element('internal', '', '', l($single), FULL_STRING . '#tab-1') . '</li>';
	$output .= '<li class="item_second">' . anchor_element('internal', '', '', l('customize'), FULL_STRING . '#tab-2') . '</li>';
	if (TABLE_PARAMETER != 'categories' && TABLE_PARAMETER != 'comments')
	{
		$output .= '<li class="item_last">' . anchor_element('internal', '', '', l('date'), FULL_STRING . '#tab-3') . '</li>';
	}
	$output .= '</ul>';

	/* collect tab box output */

	$output .= form_element('form', 'form_admin', 'js_check_required js_note_required form_admin hidden_legend', '', '', '', 'action="' . REWRITE_STRING . $string . '" method="post"');
	$output .= '<div class="js_box_tab box_tab box_tab_admin">';

	/* collect content set */

	$output .= form_element('fieldset', 'tab-1', 'js_set_tab set_tab set_tab_admin', '', '', l($single)) . '<ul>';
	if (TABLE_PARAMETER == 'comments')
	{
		$output .= '<li>' . form_element('text', 'author', 'js_required field_text_admin field_note' . $class_readonly, 'author', $author, '* ' . l('author'), 'maxlength="50" required="required" autofocus="autofocus"' . $code_readonly) . '</li>';
		$output .= '<li>' . form_element('email', 'email', 'js_required field_text_admin field_note' . $class_readonly, 'email', $email, '* ' . l('email'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
		$output .= '<li>' . form_element('url', 'url', 'field_text_admin', 'url', $url, l('url'), 'maxlength="50"') . '</li>';
	}
	else
	{
		$output .= '<li>' . form_element('text', 'title', 'js_required js_generate_alias_input field_text_admin field_note', 'title', $title, l('title'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
		$output .= '<li>' . form_element('text', 'alias', 'js_required js_generate_alias_output field_text_admin field_note', 'alias', $alias, l('alias'), 'maxlength="50" required="required"') . '</li>';
	}
	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles')
	{
		$output .= '<li>' . form_element('textarea', 'description', 'js_auto_resize field_textarea_small_admin', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
		$output .= '<li>' . form_element('textarea', 'keywords', 'js_auto_resize field_textarea_small_admin', 'keywords', $keywords, l('keywords'), 'rows="1" cols="15"') . '</li>';
	}
	if (TABLE_PARAMETER != 'categories')
	{
		$output .= '<li>' . form_element('textarea', 'text', 'js_required js_auto_resize js_editor field_textarea_admin field_note', 'text', $text, l('text'), 'rows="5" cols="100" required="required"') . '</li>';
	}
	$output .= '</ul></fieldset>';

	/* collect customize set */

	$output .= form_element('fieldset', 'tab-2', 'js_set_tab set_tab set_tab_admin', '', '', l('customize')) . '<ul>';

	/* build languages select */

	$language_array[l('select')] = '';
	$languages_directory = read_directory('languages', 'misc.php');
	foreach ($languages_directory as $value)
	{
		$value = substr($value, 0, 2);
		$language_array[l($value)] = $value;
	}
	$output .= '<li>' . select_element('language', 'field_select_admin', 'language', $language_array, $language, l('language')) . '</li>';

	/* build templates select */

	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles')
	{
		$template_array[l('select')] = '';
		$templates_directory = read_directory('templates', array('admin', 'install'));
		foreach ($templates_directory as $value)
		{
			$template_array[$value] = $value;
		}
		$output .= '<li>' . select_element('template', 'field_select_admin', 'template', $template_array, $template, l('template')) . '</li>';
	}

	/* build category select */

	if (TABLE_PARAMETER != 'comments')
	{
		if (TABLE_PARAMETER == 'extras')
		{
			$category_array[l('all')] = 0;
		}
		else
		{
			$category_array[l('none')] = 0;
		}
		$categories_query = 'SELECT id, title, parent FROM ' . PREFIX . 'categories ORDER BY rank ASC';
		$categories_result = mysql_query($categories_query);
		if ($categories_result)
		{
			while ($c = mysql_fetch_assoc($categories_result))
			{
				if (TABLE_PARAMETER != 'categories')
				{
					$category_array[$c['title']] = $c['id'];
				}
				else if (ID_PARAMETER != $c['id'] && $c['parent'] == 0)
				{
					$category_array[$c['title']] = $c['id'];
				}
			}
		}
		if (TABLE_PARAMETER == 'categories')
		{
			$output .= '<li>' . select_element('parent', 'field_select_admin', 'parent', $category_array, $parent, l('category_parent')) . '</li>';
		}
		else
		{
			$output .= '<li>' . select_element('category', 'field_select_admin', 'category', $category_array, $category, l('category')) . '</li>';
		}
	}

	/* build article select */

	if (TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
	{
		if (TABLE_PARAMETER == 'extras')
		{
			$article_array[l('all')] = 0;
		}
		$articles_query = 'SELECT id, title FROM ' . PREFIX . 'articles';
		if (TABLE_PARAMETER == 'comments')
		{
			$articles_query .= ' WHERE comments > 0';
		}
		$articles_query .= ' ORDER BY rank ASC';
		$articles_result = mysql_query($articles_query);
		if ($articles_result)
		{
			while ($a = mysql_fetch_assoc($articles_result))
			{
				$article_array[$a['title']] = $a['id'];
			}
		}
		$output .= '<li>' . select_element('article', 'field_select_admin', 'article', $article_array, $article, l('article')) . '</li>';
	}
	if (TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras')
	{
		$output .= '<li>' . select_element('headline', 'field_select_admin', 'headline', array(
			l('enable') => 1,
			l('disable') => 0
		), $headline, l('headline')) . '</li>';
	}
	if (TABLE_PARAMETER == 'articles')
	{
		$output .= '<li>' . select_element('infoline', 'field_select_admin', 'infoline', array(
			l('enable') => 1,
			l('disable') => 0
		), $infoline, l('infoline')) . '</li>';
		$output .= '<li>' . select_element('comments', 'field_select_admin', 'comments', array(
			l('enable') => 1,
			l('freeze') => 2,
			l('restrict') => 3,
			l('disable') => 0
		), $comments, l('comments')) . '</li>';
	}
	if ($status != 2)
	{
		$output .= '<li>' . select_element('status', 'field_select_admin', 'status', array(
			l('publish') => 1,
			l('unpublish') => 0
		), $status, l('status')) . '</li>';
	}

	/* build access select */

	if (GROUPS_EDIT == 1)
	{
		$access_array[l('all')] = 0;
		$access_query = 'SELECT id, name FROM ' . PREFIX . 'groups ORDER BY name ASC';
		$access_result = mysql_query($access_query);
		if ($access_result)
		{
			while ($g = mysql_fetch_assoc($access_result))
			{
				$access_array[$g['name']] = $g['id'];
			}
		}
		$output .= '<li>' . select_element('access', 'field_select_admin field_multiple', 'access', $access_array, $access, l('access'), 'multiple="multiple"') . '</li>';
	}
	$output .= '</ul></fieldset>';

	/* collect date set */

	if (TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras')
	{
		$output .= form_element('fieldset', 'tab-3', 'js_set_tab set_tab set_tab_admin', '', '', l('date')) . '<ul>';
		$output .= '<li>' . select_date('day', 'field_select_admin', 'day', $date, 'd', 1, 32, l('day')) . '</li>';
		$output .= '<li>' . select_date('month', 'field_select_admin', 'month', $date, 'M', 1, 13, l('month')) . '</li>';
		$output .= '<li>' . select_date('year', 'field_select_admin', 'year', $date, 'Y', 2000, 2016, l('year')) . '</li>';
		$output .= '<li>' . select_date('hour', 'field_select_admin', 'hour', $date, 'H', 0, 24, l('hour')) . '</li>';
		$output .= '<li>' . select_date('minute', 'field_select_admin', 'minute', $date, 'i', 0, 60, l('minute')) . '</li>';
		$output .= '</ul></fieldset>';
	}
	$output .= '</div>';

	/* collect premature output */

	if (TABLE_PARAMETER != 'comments')
	{
		$output .= form_element('hidden', '', '', 'author', MY_USER);
	}
	if ($status == 2)
	{
		$output .= form_element('hidden', '', '', 'publish', 2);
	}
	$output .= form_element('hidden', '', '', 'rank', $rank);
	$output .= form_element('hidden', '', '', 'token', TOKEN);

	/* cancel button */

	if ($edit == 1 || $delete == 1)
	{
		$cancel_string = 'admin/view/' . TABLE_PARAMETER;
	}
	else
	{
		$cancel_string = 'admin';
	}
	$output .= '<a class="js_cancel field_button_large_admin field_button_backward field_button_first" href="' . REWRITE_STRING . $cancel_string . '"><span><span>' . l('cancel') . '</span></span></a>';

	/* delete button */

	if ($delete == 1 && $id)
	{
		$output .= '<a class="js_delete js_confirm field_button_large_admin field_button_second" href="' . REWRITE_STRING . 'admin/delete/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN . '"><span><span>' . l('delete') . '</span></span></a>';
	}

	/* submit button */

	if ($new == 1 || $edit == 1)
	{
		$output .= form_element('button', '', 'js_submit field_button_large_admin field_button_forward field_button_last', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	echo $output;
	hook(__FUNCTION__ . '_end');
}
?>
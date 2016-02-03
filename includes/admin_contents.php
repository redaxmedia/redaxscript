<?php

/**
 * admin contents list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_contents_list()
{
	$output = Redaxscript\Hook::trigger('adminContentListStart');

	/* define access variables */

	$table_new = TABLE_NEW;
	if (TABLE_PARAMETER == 'comments')
	{
		$articles_total = Redaxscript\Db::forTablePrefix('articles')->count();
		$articles_comments_disable = Redaxscript\Db::forTablePrefix('articles')->where('comments', 0)->count();
		if ($articles_total == $articles_comments_disable)
		{
			$table_new = 0;
		}
	}

	/* switch table */

	switch (TABLE_PARAMETER)
	{
		case 'categories':
			$wording_single = 'category';
			$wording_parent = 'category_parent';
			break;
		case 'articles':
			$wording_single = 'article';
			$wording_parent = 'category';
			break;
		case 'extras':
			$wording_single = 'extra';
			break;
		case 'comments':
			$wording_single = 'comment';
			$wording_parent = 'article';
			break;
	}

	/* query contents */

	$result = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->orderByAsc('rank')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . l(TABLE_PARAMETER) . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if ($table_new == 1)
	{
		$output .= anchor_element('internal', '', 'rs-admin-button-default rs-admin-button-plus', l($wording_single . '_new'), 'admin/new/' . TABLE_PARAMETER);
	}
	if (TABLE_EDIT == 1 && $num_rows)
	{
		$output .= anchor_element('internal', '', 'rs-admin-button-default rs-admin-button-sort', l('sort'), 'admin/sort/' . TABLE_PARAMETER . '/' . TOKEN);
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table">';

	/* collect thead */

	$output .= '<thead><tr><th class="rs-admin-s3o6 rs-admin-column-first">' . l('title') . '</th><th class="';
	if (TABLE_PARAMETER != 'extras')
	{
		$output .= 'rs-admin-s1o6';
	}
	else
	{
		$output .= 'rs-admin-s3o6';
	}
	$output .= ' rs-admin-column-second">';
	if (TABLE_PARAMETER == 'comments')
	{
		$output .= l('identifier');
	}
	else
	{
		$output .= l('alias');
	}
	$output .= '</th>';
	if (TABLE_PARAMETER != 'extras')
	{
		$output .= '<th class="rs-admin-column-third">' . l($wording_parent) . '</th>';
	}
	$output .= '<th class="rs-admin-column-move rs-admin-column-last">' . l('rank') . '</th></tr></thead>';

	/* collect tfoot */

	$output .= '<tfoot><tr><td class="rs-admin-column-first">' . l('title') . '</td><td class="rs-admin-column-second">';
	if (TABLE_PARAMETER == 'comments')
	{
		$output .= l('identifier');
	}
	else
	{
		$output .= l('alias');
	}
	$output .= '</td>';
	if (TABLE_PARAMETER != 'extras')
	{
		$output .= '<td class="rs-admin-column-third">' . l($wording_parent) . '</td>';
	}
	$output .= '<td class="rs-admin-column-move rs-admin-column-last">' . l('rank') . '</td></tr></tfoot>';
	if ($result == '' || $num_rows == '')
	{
		$error = l($wording_single . '_no') . l('point');
	}
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

				/* prepare name */

				if (TABLE_PARAMETER == 'comments')
				{
					$name = $author . l('colon') . ' ' . strip_tags($text);
				}
				else
				{
					$name = $title;
				}

				/* build class string */

				if ($status == 1)
				{
					$class_status = '';
				}
				else
				{
					$class_status = 'row_disabled';
				}

				/* build route */

				if (TABLE_PARAMETER != 'extras' && $status == 1)
				{
					if (TABLE_PARAMETER == 'categories' && $parent == 0 || TABLE_PARAMETER == 'articles' && $category == 0)
					{
						$route = $alias;
					}
					else
					{
						$route = build_route(TABLE_PARAMETER, $id);
					}
				}
				else
				{
					$route = '';
				}

				/* collect tbody output */

				if (TABLE_PARAMETER == 'categories')
				{
					if ($before != $parent)
					{
						$output .= '<tbody><tr class="rs-row-group"><td colspan="4">';
						if ($parent)
						{
							$output .= Redaxscript\Db::forTablePrefix('categories')->where('id', $parent)->findOne()->title;
						}
						else
						{
							$output .= l('none');
						}
						$output .= '</td></tr>';
					}
					$before = $parent;
				}
				if (TABLE_PARAMETER == 'articles')
				{
					if ($before != $category)
					{
						$output .= '<tbody><tr class="rs-row-group"><td colspan="4">';
						if ($category)
						{
							$output .= Redaxscript\Db::forTablePrefix('categories')->where('id', $category)->findOne()->title;
						}
						else
						{
							$output .= l('uncategorized');
						}
						$output .= '</td></tr>';
					}
					$before = $category;
				}
				if (TABLE_PARAMETER == 'comments')
				{
					if ($before != $article)
					{
						$output .= '<tbody><tr class="rs-row-group"><td colspan="4">';
						if ($article)
						{
							$output .= Redaxscript\Db::forTablePrefix('articles')->where('id', $article)->findOne()->title;
						}
						else
						{
							$output .= l('none');
						}
						$output .= '</td></tr>';
					}
					$before = $article;
				}

				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				if ($class_status)
				{
					$output .= ' class="' . $class_status . '"';
				}
				$output .= '><td class="rs-admin-column-first">';
				if ($language)
				{
					$output .= '<span class="rs-icon-flag rs-language-' . $language . '" title="' . l($language) . '">' . $language . '</span>';
				}
				if ($status == 1)
				{
					$output .= anchor_element('internal', '', 'rs-link-view', $name, $route);
				}
				else
				{
					$output .= $name;
				}

				/* collect control output */

				$output .= admin_control('contents', TABLE_PARAMETER, $id, $alias, $status, TABLE_NEW, TABLE_EDIT, TABLE_DELETE);

				/* collect alias and id output */

				$output .= '</td><td class="rs-admin-column-second">';
				if (TABLE_PARAMETER == 'comments')
				{
					$output .= $id;
				}
				else
				{
					$output .= $alias;
				}
				$output .= '</td>';

				/* collect parent output */

				if (TABLE_PARAMETER != 'extras')
				{
					$output .= '<td class="rs-admin-column-third">';
					if (TABLE_PARAMETER == 'categories')
					{
						if ($parent)
						{
							$parent_title = Redaxscript\Db::forTablePrefix('categories')->where('id', $parent)->findOne()->title;
							$output .= anchor_element('internal', '', 'link_parent', $parent_title, 'admin/edit/categories/' . $parent);
						}
						else
						{
							$output .= l('none');
						}
					}
					if (TABLE_PARAMETER == 'articles')
					{
						if ($category)
						{
							$category_title = Redaxscript\Db::forTablePrefix('categories')->where('id', $category)->findOne()->title;
							$output .= anchor_element('internal', '', 'link_parent', $category_title, 'admin/edit/categories/' . $category);
						}
						else
						{
							$output .= l('uncategorized');
						}
					}
					if (TABLE_PARAMETER == 'comments')
					{
						if ($article)
						{
							$article_title = Redaxscript\Db::forTablePrefix('articles')->where('id', $article)->findOne()->title;
							$output .= anchor_element('internal', '', 'link_parent', $article_title, 'admin/edit/articles/' . $article);
						}
						else
						{
							$output .= l('none');
						}
					}
					$output .= '</td>';
				}
				$output .= '<td class="rs-admin-column-move rs-admin-column-last">';

				/* collect control output */

				if (TABLE_EDIT == 1)
				{
					$rank_desc = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->max('rank');
					if ($rank > 1)
					{
						$output .= anchor_element('internal', '', 'rs-admin-move-up', l('up'), 'admin/up/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN);
					}
					else
					{
						$output .= '<span class="rs-admin-move-up">' . l('up') . '</span>';
					}
					if ($rank < $rank_desc)
					{
						$output .= anchor_element('internal', '', 'rs-admin-move-down', l('down'), 'admin/down/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN);
					}
					else
					{
						$output .= '<span class="rs-admin-move-down">' . l('down') . '</span>';
					}
					$output .= '</td>';
				}
				$output .= '</tr>';

				/* collect tbody output */

				if (TABLE_PARAMETER == 'categories')
				{
					if ($before != $parent)
					{
						$output .= '</tbody>';
					}
				}
				if (TABLE_PARAMETER == 'articles')
				{
					if ($before != $category)
					{
						$output .= '</tbody>';
					}
				}
				if (TABLE_PARAMETER == 'comments')
				{
					if ($before != $article)
					{
						$output .= '</tbody>';
					}
				}
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = l('access_no') . l('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="4">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Redaxscript\Hook::trigger('adminContentListEnd');
	echo $output;
}

/**
 * admin contents form
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_contents_form()
{
	$output = Redaxscript\Hook::trigger('adminContentFormStart');

	/* switch table */

	switch (TABLE_PARAMETER)
	{
		case 'categories':
			$wording_single = 'category';
			$wording_sibling = 'category_sibling';
			break;
		case 'articles':
			$wording_single = 'article';
			$wording_sibling = 'article_sibling';
			break;
		case 'extras':
			$wording_single = 'extra';
			$wording_sibling = 'extra_sibling';
			break;
		case 'comments':
			$wording_single = 'comment';
			break;
	}

	/* define fields for existing user */

	if (ADMIN_PARAMETER == 'edit' && ID_PARAMETER)
	{
		/* query content */

		$result = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->where('id', ID_PARAMETER)->findArray();
		$r = $result[0];
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
		$route = 'admin/process/' . TABLE_PARAMETER . '/' . $id;
	}

	/* else define fields for new content */

	else if (ADMIN_PARAMETER == 'new')
	{
		if (TABLE_PARAMETER == 'comments')
		{
			$author = MY_USER;
			$email = MY_EMAIL;
			$code_readonly = ' readonly="readonly"';
		}
		if (TABLE_PARAMETER == 'categories')
		{
			$sibling = 0;
			$parent = 0;
		}
		if (TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras')
		{
			$category = 0;
			$headline = 1;
		}
		if (TABLE_PARAMETER == 'articles')
		{
			$sibling = 0;
			$infoline = 0;
			$comments = 0;
		}
		if (TABLE_PARAMETER == 'extras')
		{
			$sibling = 0;
		}
		$status = 1;
		$rank = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->max('rank') + 1;
		$access = null;
		$wording_headline = l($wording_single . '_new');
		$wording_submit = l('create');
		$route = 'admin/process/' . TABLE_PARAMETER;
	}

	/* collect output */

	$output .= '<h2 class="rs-admin-title-content">' . $wording_headline . '</h2>';
	$output .= form_element('form', 'form_admin', 'rs-js-validate-form rs-js-tab rs-admin-form', '', '', '', 'action="' . REWRITE_ROUTE . $route . '" method="post"');

	/* collect tab list output */

	$output .= '<ul class="rs-js-list-tab rs-admin-list-tab">';
	$output .= '<li class="rs-js-item-active rs-item-first rs-item-active">' . anchor_element('internal', '', '', l($wording_single), FULL_ROUTE . '#tab-1') . '</li>';
	$output .= '<li class="rs-item-second">' . anchor_element('internal', '', '', l('customize'), FULL_ROUTE . '#tab-2') . '</li>';
	if (TABLE_PARAMETER != 'categories')
	{
		$output .= '<li class="rs-item-last">' . anchor_element('internal', '', '', l('date'), FULL_ROUTE . '#tab-3') . '</li>';
	}
	$output .= '</ul>';

	/* collect tab box output */

	$output .= '<div class="rs-js-box-tab rs-admin-box-tab">';

	/* collect content set */

	$output .= form_element('fieldset', 'tab-1', 'rs-js-set-tab rs-js-set-active rs-admin-set-tab rs-set-active', '', '', l($wording_single)) . '<ul>';
	if (TABLE_PARAMETER == 'comments')
	{
		$output .= '<li>' . form_element('text', 'author', 'rs-admin-field-default rs-field-note', 'author', $author, '* ' . l('author'), 'maxlength="50" required="required" autofocus="autofocus"' . $code_readonly) . '</li>';
		$output .= '<li>' . form_element('email', 'email', 'rs-admin-field-default rs-field-note', 'email', $email, '* ' . l('email'), 'maxlength="50" required="required"' . $code_readonly) . '</li>';
		$output .= '<li>' . form_element('url', 'url', 'rs-admin-field-default', 'url', $url, l('url'), 'maxlength="50"') . '</li>';
	}
	else
	{
		$output .= '<li>' . form_element('text', 'title', 'rs-js-generate-alias-input rs-admin-field-default rs-field-note', 'title', $title, l('title'), 'maxlength="50" required="required" autofocus="autofocus"') . '</li>';
		$output .= '<li>' . form_element('text', 'alias', 'rs-js-generate-alias-output rs-admin-field-default rs-field-note', 'alias', $alias, l('alias'), 'maxlength="50" required="required"') . '</li>';
	}
	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles')
	{
		$output .= '<li>' . form_element('textarea', 'description', 'rs-js-auto-resize rs-admin-field-textarea rs-field-small', 'description', $description, l('description'), 'rows="1" cols="15"') . '</li>';
		$output .= '<li>' . form_element('textarea', 'keywords', 'rs-js-auto-resize rs-js-generate-keyword-output rs-admin-field-textarea rs-field-small', 'keywords', $keywords, l('keywords'), 'rows="1" cols="15"') . '</li>';
	}
	if (TABLE_PARAMETER != 'categories')
	{
		$output .= '<li>' . form_element('textarea', 'text', 'rs-js-auto-resize rs-js-generate-keyword-input rs-js-editor-textarea rs-admin-field-textarea rs-field-note', 'text', $text, l('text'), 'rows="5" cols="100" required="required"') . '</li>';
	}
	$output .= '</ul></fieldset>';

	/* collect customize set */

	$output .= form_element('fieldset', 'tab-2', 'rs-js-set-tab rs-admin-set-tab', '', '', l('customize')) . '<ul>';

	/* languages directory object */

	$languages_directory = new Redaxscript\Directory();
	$languages_directory->init('languages');
	$languages_directory_array = $languages_directory->getArray();

	/* build languages select */

	$language_array[l('select')] = '';
	foreach ($languages_directory_array as $value)
	{
		$value = substr($value, 0, 2);
		$language_array[l($value, '_index')] = $value;
	}
	$output .= '<li>' . select_element('language', 'rs-admin-field-select', 'language', $language_array, $language, l('language')) . '</li>';
	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles')
	{
		/* templates directory object */

		$templates_directory = new Redaxscript\Directory();
		$templates_directory->init('templates', array(
			'admin',
			'install'
		));
		$templates_directory_array = $templates_directory->getArray();

		/* build templates select */

		$template_array[l('select')] = '';
		foreach ($templates_directory_array as $value)
		{
			$template_array[$value] = $value;
		}
		$output .= '<li>' . select_element('template', 'rs-admin-field-select', 'template', $template_array, $template, l('template')) . '</li>';
	}

	/* build sibling select */

	if (TABLE_PARAMETER == 'categories' || TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras')
	{
		$sibling_array[l('none')] = 0;
		$sibling_result = Redaxscript\Db::forTablePrefix(TABLE_PARAMETER)->orderByAsc('rank')->findArray();
		if ($sibling_result)
		{
			foreach ($sibling_result as $s)
			{
				if (ID_PARAMETER != $s['id'])
				{
					$sibling_array[$s['title'] . ' (' . $s['id'] . ')'] = $s['id'];
				}
			}
		}
		$output .= '<li>' . select_element('sibling', 'rs-admin-field-select', 'sibling', $sibling_array, $sibling, l($wording_sibling)) . '</li>';
	}

	/* build category and parent select */

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
		$categories_result = Redaxscript\Db::forTablePrefix('categories')->orderByAsc('rank')->findArray();
		if ($categories_result)
		{
			foreach ($categories_result as $c)
			{
				if (TABLE_PARAMETER != 'categories')
				{
					$category_array[$c['title'] . ' (' . $c['id'] . ')'] = $c['id'];
				}
				else if (ID_PARAMETER != $c['id'] && $c['parent'] == 0)
				{
					$category_array[$c['title'] . ' (' . $c['id'] . ')'] = $c['id'];
				}
			}
		}
		if (TABLE_PARAMETER == 'categories')
		{
			$output .= '<li>' . select_element('parent', 'rs-admin-field-select', 'parent', $category_array, $parent, l('category_parent')) . '</li>';
		}
		else
		{
			$output .= '<li>' . select_element('category', 'rs-admin-field-select', 'category', $category_array, $category, l('category')) . '</li>';
		}
	}

	/* build article select */

	if (TABLE_PARAMETER == 'extras' || TABLE_PARAMETER == 'comments')
	{
		if (TABLE_PARAMETER == 'extras')
		{
			$article_array[l('all')] = 0;
		}
		$articles = Redaxscript\Db::forTablePrefix('articles');
		if (TABLE_PARAMETER == 'comments')
		{
			$articles->where('comments', 0);
		}
		$articles_result = $articles->orderByAsc('rank')->findArray();
		if ($articles_result)
		{
			foreach ($articles_result as $a)
			{
				$article_array[$a['title'] . ' (' . $a['id'] . ')'] = $a['id'];
			}
		}
		$output .= '<li>' . select_element('article', 'rs-admin-field-select', 'article', $article_array, $article, l('article')) . '</li>';
	}
	if (TABLE_PARAMETER == 'articles' || TABLE_PARAMETER == 'extras')
	{
		$output .= '<li>' . select_element('headline', 'rs-admin-field-select', 'headline', array(
			l('enable') => 1,
			l('disable') => 0
		), $headline, l('headline')) . '</li>';
	}
	if (TABLE_PARAMETER == 'articles')
	{
		$output .= '<li>' . select_element('infoline', 'rs-admin-field-select', 'infoline', array(
			l('enable') => 1,
			l('disable') => 0
		), $infoline, l('infoline')) . '</li>';
		$output .= '<li>' . select_element('comments', 'rs-admin-field-select', 'comments', array(
			l('enable') => 1,
			l('freeze') => 2,
			l('restrict') => 3,
			l('disable') => 0
		), $comments, l('comments')) . '</li>';
	}
	if ($status != 2)
	{
		$output .= '<li>' . select_element('status', 'rs-admin-field-select', 'status', array(
			l('publish') => 1,
			l('unpublish') => 0
		), $status, l('status')) . '</li>';
	}

	/* build access select */

	if (GROUPS_EDIT == 1)
	{
		$access_array[l('all')] = null;
		$access_result = Redaxscript\Db::forTablePrefix('groups')->orderByAsc('name')->findArray();
		if ($access_result)
		{
			foreach ($access_result as $g)
			{
				$access_array[$g['name']] = $g['id'];
			}
		}
		$output .= '<li>' . select_element('access', 'rs-admin-field-select', 'access', $access_array, $access, l('access'), 'multiple="multiple"') . '</li>';
	}
	$output .= '</ul></fieldset>';

	/* collect date set */

	if (TABLE_PARAMETER != 'categories')
	{
		$output .= form_element('fieldset', 'tab-3', 'rs-js-set-tab rs-admin-set-tab', '', '', l('date')) . '<ul>';
		$output .= '<li>' . select_date('day', 'rs-admin-field-select', 'day', $date, 'd', 1, 32, l('day')) . '</li>';
		$output .= '<li>' . select_date('month', 'rs-admin-field-select', 'month', $date, 'm', 1, 13, l('month')) . '</li>';
		$output .= '<li>' . select_date('year', 'rs-admin-field-select', 'year', $date, 'Y', 2000, 2021, l('year')) . '</li>';
		$output .= '<li>' . select_date('hour', 'rs-admin-field-select', 'hour', $date, 'H', 0, 24, l('hour')) . '</li>';
		$output .= '<li>' . select_date('minute', 'rs-admin-field-select', 'minute', $date, 'i', 0, 60, l('minute')) . '</li>';
		$output .= '</ul></fieldset>';
	}
	$output .= '</div>';

	/* collect hidden output */

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

	if (TABLE_EDIT == 1 || TABLE_DELETE == 1)
	{
		$cancel_route = 'admin/view/' . TABLE_PARAMETER;
	}
	else
	{
		$cancel_route = 'admin';
	}
	$output .= anchor_element('internal', '', 'rs-js-cancel rs-admin-button-default rs-admin-button-cancel', l('cancel'), $cancel_route);

	/* delete button */

	if (TABLE_DELETE == 1 && $id)
	{
		$output .= anchor_element('internal', '', 'rs-js-delete rs-js-confirm rs-admin-button-default rs-admin-button-delete', l('delete'), 'admin/delete/' . TABLE_PARAMETER . '/' . $id . '/' . TOKEN);
	}

	/* submit button */

	if (TABLE_NEW == 1 || TABLE_EDIT == 1)
	{
		$output .= form_element('button', '', 'rs-js-submit rs-admin-button-default rs-admin-button-submit', ADMIN_PARAMETER, $wording_submit);
	}
	$output .= '</form>';
	$output .= Redaxscript\Hook::trigger('adminContentFormEnd');
	echo $output;
}

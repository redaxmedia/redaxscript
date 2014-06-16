<?php

/**
 * fb group loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function fb_group_loader_start()
{
	global $loader_modules_styles;
	$loader_modules_styles[] = 'modules/fb_group/styles/fb_group.css';
}

/**
 * fb group render start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function fb_group_render_start()
{
	if (FIRST_PARAMETER == 'fb-group' && SECOND_PARAMETER == 'get-contents' && (THIRD_PARAMETER == 'members' || THIRD_PARAMETER == 'feed'))
	{
		define('RENDER_BREAK', 1);
		fb_group_get_contents(THIRD_PARAMETER);
	}
}

/**
 * fb group
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $type
 * @param array $options
 */

function fb_group($type = '', $options = '')
{
	/* define option variables */

	if (is_array($options))
	{
		foreach ($options as $key => $value)
		{
			$key = 'option_' . $key;
			$$key = $value;
		}
	}

	/* fallback */

	if ($option_limit_members == '')
	{
		$option_limit_members = s('limit');
	}
	if ($option_limit_messages == '')
	{
		$option_limit_messages = s('limit');
	}
	if ($option_limit_comments == '')
	{
		$option_limit_comments = s('limit');
	}

	/* get contents */

	$contents = fb_group_get_contents($type);

	/* decode contents */

	if ($contents)
	{
		$contents = json_decode($contents);
		$data = $contents->data;
	}

	/* collect members output */

	if ($data && $type == 'members')
	{
		$output = '<ul class="list_fb_group_members">';
		foreach ($data as $value)
		{
			/* break if limit reached */

			if (++$members_counter > $option_limit_members)
			{
				break;
			}

			/* collect members output */

			$output .= '<li>' . fb_group_user_link($value->id, $value->name) . '</li>';
		}
		$output .= '</ul>';
	}

	/* collect feed output */

	if ($data && $type == 'feed')
	{
		foreach ($data as $value)
		{
			/* break if limit reached */

			if (++$messages_counter > $option_limit_messages)
			{
				break;
			}

			/* collect message output */

			$output .= '<div class="box_fb_group_message clear_fix">' . fb_group_user_image($value->from->id, $value->from->name, 'square', 1);
			$output .= '<div class="wrapper_fb_group_message_sub">';
			$output .= '<h3 class="title_fb_group_message_sub">' . fb_group_user_link($value->from->id, $value->from->name) . '</h3>';

			/* message fallback */

			if ($value->message == '')
			{
				$value->message = $value->application->name;
			}
			$output .= '<div class="box_fb_group_message_sub">' . fb_group_parser($value->message) . '</div>';
			$output .= '</div></div>';

			/* collect likes output */

			$likes = $value->likes->data;
			$likes_total = count($likes);
			$likes_limit = $option_limit_members - 1;
			$likes_counter = 0;
			$likes_rest = 0;

			/* if likes present */

			if ($likes_total > 1)
			{
				$output .= '<div class="box_fb_group_like_infoline">';
				foreach ($likes as $like_value)
				{
					$output .= fb_group_user_link($like_value->id, $like_value->name);

					/* break if limit reached */

					if (++$likes_counter > $likes_limit)
					{
						break;
					}

					/* collect likes output */

					if ($likes_counter == $likes_total - 1)
					{
						$output .= ' ' . l('and', 'fb_group') . ' ';
					}
					else if ($likes_total > 1 && $likes_counter < $likes_total - 1)
					{
						$output .= ', ';
					}
				}
				$likes_rest = $likes_total - $likes_counter;

				/* collect likes wording output */

				$output .= ' ';
				if ($likes_total == 1)
				{
					$output .= l('likes_this', 'fb_group');
				}
				else if ($likes_rest)
				{
					$output .= l('and', 'fb_group') . ' ' . $likes_rest . ' ' . l('other', 'fb_group') . ' ' . l('like_this', 'fb_group');
				}
				else
				{
					$output .= l('like_this', 'fb_group');
				}
				$output .= l('point') . '</div>';
			}

			/* collect related output */

			$comments = $value->comments->data;
			$comments_total = $value->comments->count;
			$comments_counter = 0;
			if ($comments)
			{
				$output .= '<div class="box_fb_group_comment_infoline">' . $comments_total . ' ';
				if ($comments_total == 1)
				{
					$output .= l('comment');
				}
				else
				{
					$output .= l('comments');
				}
				$output .= '</div>';
				foreach ($comments as $comment_value)
				{
					/* break if limit reached */

					if (++$comments_counter > $option_limit_comments)
					{
						break;
					}

					/* collect comment output */

					$output .= '<div class="box_fb_group_comment clear_fix">' . fb_group_user_image($comment_value->from->id, $comment_value->from->name, 'square', 1);
					$output .= '<div class="wrapper_fb_group_comment_sub">';
					$output .= '<h4 class="title_fb_group_comment_sub">' . fb_group_user_link($comment_value->from->id, $comment_value->from->name) . '</h4>';
					$output .= '<div class="box_fb_group_comment_sub">' . fb_group_parser($comment_value->message) . '</div>';
					$output .= '</div></div>';
				}
				if (++$comments_counter > $option_limit_comments)
				{
					$output .= '<div class="box_fb_group_comment_read_more clear_fix">' . anchor_element('external', '', 'js_confirm link_fb_group_comment_read_more', l('read_more'), FB_GROUP_GROUP_URL) . '</div>';
				}
			}
		}
	}
	echo $output;
}

/**
 * fb group parser
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $message
 * @return string
 */

function fb_group_parser($message = '')
{
	$output = htmlspecialchars($message);
	$search = '/(https?:\/\/[^\s]+)/';

	/* get all matches */

	preg_match_all($search, $output, $matches);
	$matches = $matches[0];

	/* replace each url */

	foreach ($matches as $url)
	{
		$link = anchor_element('', '', 'js_confirm link_default', $url, $url);
		$output = str_replace($url, $link, $output);
	}
	return $output;
}

/**
 * fb group user link
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param integer $id
 * @param string $name
 * @return string
 */

function fb_group_user_link($id = '', $name = '')
{
	$output = anchor_element('external', '', 'link_fb_group_user', $name, FB_GROUP_FACEBOOK_URL . '/profile.php?id=' . $id, '', 'rel="nofollow"');
	return $output;
}

/**
 * fb group user image
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param integer $id
 * @param string $name
 * @param string $type
 * @param integer $mode
 * @return string
 */

function fb_group_user_image($id = '', $name = '', $type = '', $mode = '')
{
	if ($mode == 1)
	{
		$output = '<a class="link_fb_group_user_image" href="' . FB_GROUP_FACEBOOK_URL . '/profile.php?id=' . $id . '" title="' . $name . '" rel="nofollow">';
	}
	$output .= '<img class="image_fb_group_' . $type . '" src="' . FB_GROUP_IMG_URL . '/' . $id . '/picture?type=' . $type . '" alt="' . $name . '" />';
	if ($mode == 1)
	{
		$output .= '</a>';
	}
	return $output;
}

/**
 * fb group get access token
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @return string
 */

function fb_group_get_access_token()
{
	static $output;

	/* get token */

	if ($output == '')
	{
		/* get contents */

		$url = FB_GROUP_API_URL . '/oauth/access_token?client_id=' . FB_GROUP_APP_ID . '&client_secret=' . FB_GROUP_APP_SECRET . '&grant_type=client_credentials';
		$contents = file_get_contents($url);

		/* remove access token string */

		if ($contents)
		{
			$output = str_replace('access_token=', '', $contents);
		}
	}
	return $output;
}

/**
 * fb group get contents
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param string $type
 * @return string
 */

function fb_group_get_contents($type = '')
{
	/* define variables */

	$file_path = FB_GROUP_CACHE_PATH . '/' . $type . '.json';
	$file_size = filesize($file_path);
	$file_time = filectime($file_path);
	$file_age = time() - $file_time;
	$cache_expires = constant('FB_GROUP_CACHE_' . strtoupper($type) . '_EXPIRES');

	/* load contents from cache */

	if ($file_size && $file_age < $cache_expires)
	{
		$output = file_get_contents($file_path);
	}

	/* else request contents */

	else
	{
		/* get access token */

		$access_token = fb_group_get_access_token();
		if ($access_token)
		{
			/* curl contents */

			$url = FB_GROUP_API_URL . '/' . FB_GROUP_GROUP_ID . '/' . $type. '/?access_token=' . $access_token;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			$output = curl_exec($ch);
			curl_close($ch);

			/* write cache file */

			if (is_writable($file_path))
			{
				file_put_contents($file_path, $output);
			}
		}
	}
	return $output;
}


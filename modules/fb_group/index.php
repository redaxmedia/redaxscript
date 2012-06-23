<?php

/* fb group loader start */

function fb_group_loader_start()
{
	global $loader_modules_styles;
	$loader_modules_styles[] = 'modules/fb_group/styles/fb_group.css';
}

/* fb group */

function fb_group($type = '', $limit_first = '', $limit_second = '')
{
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

			if (++$counter > $limit_first && $limit_first)
			{
				break;
			}
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

			if (++$counter_message > $limit_first && $limit_first)
			{
				break;
			}

			/* collect message output */

			$output .= '<div class="box_fb_group_message clear_fix">' . fb_group_user_image($value->from->id, $value->from->name, 'square', 1);
			$output .= '<div class="wrapper_fb_group_message_sub">';
			$output .= '<h3 class="title_fb_group_message_sub">' . fb_group_user_link($value->from->id, $value->from->name) . '</h3>';
			if ($value->message == '')
			{
				$value->message = $value->application->name;
			}
			$output .= '<div class="box_fb_group_message_sub">' . htmlspecialchars($value->message) . '</div>';
			$output .= '</div></div>';

			/* collect comments output */

			$comments = $value->comments->data;
			$comments_total = $value->comments->count;
			$counter_comment = 0;
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
				foreach ($comments as $value)
				{
					if ($counter_comment++ < $limit_second && $limit_second)
					{
						$output .= '<div class="box_fb_group_comment clear_fix">' . fb_group_user_image($value->from->id, $value->from->name, 'square', 1);
						$output .= '<div class="wrapper_fb_group_comment_sub">';
						$output .= '<h4 class="title_fb_group_comment_sub">' . fb_group_user_link($value->from->id, $value->from->name) . '</h4>';
						$output .= '<div class="box_fb_group_comment_sub">' . htmlspecialchars($value->message) . '</div>';
						$output .= '</div></div>';
					}
				}
			}
		}
	}
	echo $output;
}

/* fb group user link */

function fb_group_user_link($id = '', $name = '')
{
	$output = anchor_element('external', '', 'link_fb_group_user', $name, FB_GROUP_FACEBOOK_URL . '/profile.php?id=' . $id, '', 'nofollow');
	return $output;
}

/* fb group user image */

function fb_group_user_image($id = '', $name = '', $type = '', $mode = '')
{
	if ($mode == 1)
	{
		$output = '<a class="link_fb_group_user_image" href="' . FB_GROUP_FACEBOOK_URL . '/profile.php?id=' . $id . '" title="' . $name . '" rel="nofollow">';
	}
	$output .= '<img class="image_fb_group_' . $type . '" src="' . FB_GROUP_GRAPH_URL . '/' . $id . '/picture?type=' . $type . '" alt="' . $name . '" />';
	if ($mode == 1)
	{
		$output .= '</a>';
	}
	return $output;
}

/* fb group get access token*/

function fb_group_get_access_token()
{
	static $output;

	if ($output == '')
	{
		/* get contents */

		$url = FB_GROUP_GRAPH_URL . '/oauth/access_token?client_id=' . FB_GROUP_APP_ID . '&client_secret=' . FB_GROUP_APP_SECRET . '&grant_type=client_credentials';
		$contents = file_get_contents($url);

		/* remove access token string */

		if ($contents)
		{
			$output = str_replace('access_token=', '', $contents);
		}
	}
	return $output;
}

/* fb group get contents */

function fb_group_get_contents($type = '')
{
	/* define variables */

	$file_path = FB_GROUP_CACHE_PATH . '/fb_group_' . $type . '.json';
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
			/* get contents */

			$url = FB_GROUP_GRAPH_URL . '/' . FB_GROUP_GROUP_ID . '/' . $type. '/?access_token=' . $access_token;
			$output = file_get_contents($url);

			/* write cache file if writable */

			if (is_writable($file_path))
			{
				$file = fopen($file_path, 'w+');
				fwrite($file, $output);
				fclose($file);
			}
		}
	}
	return $output;
}
?>
<?php

/**
 * feed reader loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 */

function feed_reader_loader_start()
{
	global $loader_modules_styles;
	$loader_modules_styles[] = 'modules/feed_reader/styles/feed_reader.css';
}

/**
 * feed reader
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Module
 * @author Henry Ruhs
 *
 * @param string $url
 * @param array $options
 */

function feed_reader($url = '', $options = '')
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

	if ($option_truncate_title == '')
	{
		$option_truncate_title = 80;
	}
	if ($option_truncate_text == '')
	{
		$option_truncate_text = 1000;
	}
	if ($option_limit == '')
	{
		$option_limit = s('limit');
	}

	/* get contents */

	$contents = file_get_contents($url);
	if ($contents)
	{
		$feed = new SimpleXMLElement($contents);

		/* detect feed type */

		if ($feed->entry)
		{
			$type = 'atom';
			$feed_object = $feed->entry;
		}
		else if ($feed->channel)
		{
			$type = 'rss';
			$feed_object = $feed->channel->item;
		}

		/* collect output */

		foreach ($feed_object as $value)
		{
			/* define variables */

			$title = entity(trim($value->title));
			if ($title)
			{
				$title = truncate(strip_tags($title), $option_truncate_title, '...');
			}

			/* if atom feed */

			if ($type == 'atom')
			{
				$route = $value->link['href'];
				$time = date(s('time'), strtotime($value->updated));
				$date = date(s('date'), strtotime($value->updated));
				$text = entity(trim($value->content));
			}

			/* else if rss feed */

			else if ($type == 'rss')
			{
				$route = $value->link;
				$time = date(s('time'), strtotime($value->pubDate));
				$date = date(s('date'), strtotime($value->pubDate));
				$text = entity(trim($value->description));
			}
			if ($text)
			{
				$text = truncate(strip_tags($text, '<a>'), $option_truncate_text, '...');
			}

			/* if filter is invalid */

			if ($option_filter == '')
			{
				$filter_no = 1;
			}
			else
			{
				$position_title = strpos($title, $option_filter);
				$position_text = strpos($text, $option_filter);
				$filter_no = 0;
			}
			if ($filter_no || $position_title || $position_text)
			{
				/* break if limit reached */

				if (++$counter > $option_limit)
				{
					break;
				}

				/* collect title output */

				if ($title)
				{
					$output .= '<h3 class="title_feed_reader clear_fix">';
					if ($route)
					{
						$output .= anchor_element('external', '', 'title_first', $title, $route, '', 'rel="nofollow"');
					}
					else
					{
						$output .= '<span class="title_first">' . $title . '</span>';
					}

					/* collect date output */

					if ($time && $date)
					{
						$output .= '<span class="title_second">' . $date . ' ' . l('at') . ' ' . $time . '</span>';
					}
					$output .= '</h3>';
				}

				/* collect text output */

				if ($text)
				{
					$output .= '<div class="box_feed_reader">' . $text . '</div>';
				}
			}
		}
	}
	echo $output;
}

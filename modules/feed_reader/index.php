<?php

/* feed reader loader start */

function feed_reader_loader_start()
{
	global $loader_modules_styles;
	$loader_modules_styles[] = 'modules/feed_reader/styles/feed_reader.css';
}

/* feed reader */

function feed_reader($url = '', $filter = '', $limit = '')
{
	/* define variables */

	if (is_numeric($filter))
	{
		$limit = $filter;
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
			if (++$counter > $limit && $limit)
			{
				break;
			}

			/* define variables */

			$title = entity(truncate($value->title, 80, '...'));

			/* if atom feed */

			if ($type == 'atom')
			{
				$string = $value->link['href'];
				$time = date(s('time'), strtotime($value->updated));
				$date = date(s('date'), strtotime($value->updated));
				$text = entity(truncate($value->content, 1000, '...'));
			}

			/* else if rss feed */

			else if ($type == 'rss')
			{
				$string = $value->link;
				$time = date(s('time'), strtotime($value->pubDate));
				$date = date(s('date'), strtotime($value->pubDate));
				$text = entity(truncate($value->description, 1000, '...'));
			}

			/* if filter is invalid */

			if (is_numeric($filter) || $filter == '')
			{
				$filter_no = 1;
			}
			else
			{
				$position_title = strpos($title, $filter);
				$position_text = strpos($text, $filter);
				$filter_no = 0;
			}
			if ($filter_no || $position_title || $position_text)
			{
				/* collect title output */

				if ($title)
				{
					$output .= '<h3 class="title_feed_reader clear_fix">';
					if ($string)
					{
						$output .= anchor_element('external', '', 'title_first', $title, $string, '', 'nofollow');
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
?>
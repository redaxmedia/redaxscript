<?php

/* db backup render start */

function db_backup_render_start()
{
	if (LOGGED_IN == TOKEN && FIRST_PARAMETER == 'admin' && SECOND_PARAMETER == 'db-backup')
	{
		define('TITLE', l('db_backup_database_backup'));

		/* download database backup */

		if (THIRD_PARAMETER == 'download')
		{
			define('RENDER_BREAK', 1);
			db_backup(0);
		}

		/* email database backup */

		if (THIRD_PARAMETER == 'send')
		{
			$url_link = anchor_element('', '', '', ROOT, ROOT);
			$file_name = d('name') . '-' . db_backup_clean_date(NOW) . '.sql';
			$body_array = array(
				l('url') => $url_link,
				l('database') => d('name'),
				code1 => '<br />',
				l('message') => l('db_backup_save_attachment') . l('point')
			);
			$attachment_array = array(
				$file_name => db_backup(1)
			);
			send_mail(s('email'), s('author'), s('email'), s('author'), l('db_backup_database_backup'), $body_array, $attachment_array);
		}
	}
}

/* db backup center */

function db_backup_center_start()
{
	if (FIRST_PARAMETER == 'admin' && SECOND_PARAMETER == 'db-backup' && THIRD_PARAMETER == 'send')
	{
		define('CENTER_BREAK', 1);
		notification(l('operation_completed'), '', l('continue'), 'admin');
	}
}

/* db backup panel modules */

function db_backup_admin_panel_list_modules()
{
	$output = '<li>' . anchor_element('', '', '', l('db_backup_database_backup')) . '<ul>';
	$output .= '<li>' . anchor_element('internal', '', '', l('db_backup_download'), 'admin/db-backup/download') . '</li>';
	$output .= '<li>' . anchor_element('internal', '', '', l('db_backup_send_email'), 'admin/db-backup/send') . '</li>';
	$output .= '</ul></li>';
	return $output;
}

/* db backup */

function db_backup($mode = '')
{
	if ($mode == 0)
	{
		$file_name = d('name') . '_' . db_backup_clean_date(NOW) . '.sql';
		header('content-type: application/octet-stream');
		header('content-disposition: attachment; filename="' . $file_name . '"');
		echo db_backup_process();
	}
	else
	{
		return db_backup_process();
	}
}

/* db backup clean date */

function db_backup_clean_date($input = '')
{
	$output = preg_replace('/[-|:|+|\s]/i', '_', $input);
	$output = preg_replace('/[^0-9_]/i', '', $output);
	return $output;
}

/* db backup process */

function db_backup_process()
{
	$query = 'SHOW TABLES FROM ' . d('name');
	$result = mysql_query($query);
	while ($r = mysql_fetch_row($result))
	{
		$table = $r[0];
		$definitions .= db_backup_get_definitions($table) . PHP_EOL . PHP_EOL;
		$contents .= db_backup_get_contents($table) . PHP_EOL . PHP_EOL;
	}
	$output = $definitions . $contents;
	return $output;
}

/* db backup get definitions */

function db_backup_get_definitions($table = '')
{
	/* query columns */

	$query = 'SHOW FULL COLUMNS FROM ' . $table;
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	
	/* collect output */
	
	$output = 'CREATE TABLE IF NOT EXISTS ' . $table . ' (';
	while ($a = mysql_fetch_assoc($result))
	{
		if ($a)
		{
			foreach ($a as $key => $value)
			{
				$key = strtolower($key);
				$$key = stripslashes($value);
			}
		}
		$output .= $field . ' ' . $type;
		if ($null == 'NO')
		{
			$output .= ' NOT NULL';
		}
		if ($collation != '')
		{
			$output .= ' COLLATE ' . $collation;
		}
		if ($default)
		{
			$output .= ' DEFAULT ' . $default;
		}
		if ($extra)
		{
			$output .= ' ' . $extra;
		}
		if (++$counter < $num_rows)
		{
			$output .= ', ';
		}
	}
	
	/* query table keys */
	
	$query = 'SHOW KEYS FROM ' . $table;
	$result = mysql_query($query);
	while ($b = mysql_fetch_assoc($result))
	{
		if ($b)
		{
			foreach ($b as $key => $value)
			{
				$key = strtolower($key);
				$$key = stripslashes($value);
			}
		}
		$r[$key_name][] = $column_name;
	}
	
	/* collect output */
	
	foreach ($r as $key => $value)
	{
		if ($key == 'PRIMARY')
		{
			$value_string = implode($value, ', ');
			$output .= ', PRIMARY KEY (' . $value_string . ')';
		}
	}
	$output .= ')';
	
	/* query table status */
	
	$query = 'SHOW TABLE STATUS LIKE \'' . $table . '\'';
	$result = mysql_query($query);

	/* collect output */

	while ($c = mysql_fetch_assoc($result))
	{
		if ($c)
		{
			foreach ($c as $key => $value)
			{
				$key = strtolower($key);
				$$key = stripslashes($value);
			}
		}
		if ($engine)
		{
			$output .= ' ENGINE = ' . $engine;
		}
		if ($collation != '')
		{
			$output .= ' COLLATE = ' . $collation;
		}
		if ($auto_increment)
		{
			$output .= ' AUTO_INCREMENT = ' . $auto_increment;
		}
	}
	$output .= ';';
	return $output;
}

/* db backup get contents */

function db_backup_get_contents($table = '')
{
	/* query table contents */

	$query = 'SELECT * FROM ' . $table;
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	$num_fields = mysql_num_fields($result);
	
	/* collect output */
	
	$output = 'INSERT INTO ' . $table . ' VALUES ';
	while ($r = mysql_fetch_row($result))
	{
		$output .= '(';
		for ($i = 0; $i < $num_fields; $i++)
		{
			if ($r[$i])
			{
				$output .= '\'' . addslashes($r[$i]) . '\'';
			}
			else
			{
				$output .= '\'\'';
			}
			if ($i < $num_fields - 1)
			{
				$output .= ', ';
			}
		}
		$output .= ')';
		if (++$counter < $num_rows)
		{
			$output .= ', ';
		}
	}
	$output .= ';';
	return $output;
}
?>
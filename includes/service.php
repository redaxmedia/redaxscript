<?php

/* send mail */

function send_mail($to = '', $to_name = '', $from = '', $from_name = '', $subject = '', $body_array = '', $attachment_array = '')
{
	/* validate input */

	if ($to_name == '')
	{
		$to_name = $to;
	}
	if ($from_name == '')
	{
		$from_name = $from;
	}

	/* build email strings */

	$to_string = $to_name . ' <' . $to . '>';
	$from_string = $from_name . ' <' . $from . '>';
	$default_subject = s('subject');
	if ($default_subject)
	{
		$subject_string = $default_subject . ' - ' . $subject;
	}
	else
	{
		$subject_string = $subject;
	}

	/* collect body */

	if (is_array($body_array))
	{
		foreach ($body_array as $key => $value)
		{
			if ($key && $value)
			{
				$key_check = substr($key, 0, 4);
				if ($key_check == 'code')
				{
					$body .= $value;
				}
				else
				{
					$body .= '<strong>' . $key . ':</strong> ' . $value . '<br />';
				}
			}
		}
	}

	/* collect header */

	$header = 'mime-version: 1.0' . PHP_EOL;
	if (is_array($attachment_array))
	{
		foreach ($attachment_array as $file_name => $file_contents)
		{
			$file_contents = chunk_split(base64_encode($file_contents));
			$header .= 'content-type: multipart/mixed; boundary="' . TOKEN . '"' . PHP_EOL . PHP_EOL;
			$header .= '--' . TOKEN . PHP_EOL;
			if ($body)
			{
				$header .= 'content-type: text/html; charset=' . s('charset') . PHP_EOL;
				$header .= 'content-transfer-encoding: 8bit' . PHP_EOL . PHP_EOL;
				$header .= $body . PHP_EOL . PHP_EOL;
				$header .= '--' . TOKEN . PHP_EOL;
				$body = '';
			}
			$header .= 'content-type: application/octet-stream; name="' . $file_name . '"' . PHP_EOL;
			$header .= 'content-transfer-encoding: base64' . PHP_EOL;
			$header .= 'content-disposition: attachment; filename="' . $file_name . '"' . PHP_EOL . PHP_EOL;
			$header .= $file_contents . PHP_EOL . PHP_EOL;
			$header .= '--' . TOKEN . '--';
		}
	}
	else
	{
		$header .= 'content-type: text/html; charset=' . s('charset') . PHP_EOL;
		$header .= 'return-path: <' . $from . '>';
	}
	$header .= 'from: ' . $from_string . PHP_EOL;
	$header .= 'reply-to: ' . $from_string . PHP_EOL;
	if (function_exists('mail'))
	{
		mail($to_string, $subject_string, $body, $header);
	}
}

/* curl contents */

function curl_contents($url = '', $referer = '', $cookies = '')
{
	if (function_exists('curl_version'))
	{
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		if ($referer)
		{
			curl_setopt($handle, CURLOPT_REFERER, $referer);
		}
		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $_POST);

		/* handle cookies */

		if ($cookies)
		{
			curl_setopt($handle, CURLOPT_COOKIEJAR, $cookies);
			curl_setopt($handle, CURLOPT_COOKIEFILE, $cookies);
		}
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_TIMEOUT, 20);

		/* collect output */

		$output = curl_exec($handle);
		curl_close($handle);
		return $output;
	}
}
?>
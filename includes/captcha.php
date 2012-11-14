<?php

/**
 * captcha
 *
 * @param string $mode
 * @return integer|string
 */

function captcha($mode = '')
{
	/* task */

	if ($mode == 'task')
	{
		/* random numbers */

		$a = mt_rand(2, 10);
		$b = mt_rand(1, $a - 1);

		/* switch captcha mode */

		switch (s('captcha'))
		{
			case 2:
				$c = 1;
				break;
			case 3:
				$c = 2;
				break;
			default:
				$c = mt_rand(1, 2);
				break;
		}

		/* switch between plus and minus */

		switch ($c)
		{
			case 1:
				$_SESSION[ROOT . '/captcha'] = sha1($a + $b);
				$operator = 'plus';
				break;
			case 2:
				$_SESSION[ROOT . '/captcha'] = sha1($a - $b);
				$operator = 'minus';
				break;
		}
		$output = l($a) . ' ' . l($operator) . ' ' . l($b);
	}

	/* solution */

	if ($mode == 'solution')
	{
		$output = $_SESSION[ROOT . '/captcha'];
	}
	return $output;
}
?>
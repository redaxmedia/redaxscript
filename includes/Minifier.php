<?php
namespace Redaxscript;

/**
 * parent class to minify styles and scripts
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Minifier
 * @author Henry Ruhs
 */

class Minifier
{
	/**
	 * shortcut for styles
	 *
	 * @since 2.2.0
	 *
	 * @param string $input related styles input
	 *
	 * @return string
	 */

	public function styles($input = null)
	{
		return $this->_minify($input, 'styles');
	}

	/**
	 * shortcut for scripts
	 *
	 * @since 2.2.0
	 *
	 * @param string $input related scripts input
	 *
	 * @return string
	 */

	public function scripts($input = null)
	{
		return $this->_minify($input, 'scripts');
	}

	/**
	 * minify styles and scripts
	 *
	 * @since 2.2.0
	 *
	 * @param string $input styles and scripts input
	 * @param string $type related type of input
	 *
	 * @return string
	 */

	protected function _minify($input = null, $type = null)
	{
		/* replace comments */

		$output = preg_replace('/\/\*([\s\S]*?)\*\//', '', $input);

		/* replace tabs and newlines */

		$output = preg_replace('/\t+/', '', $output);
		$output = preg_replace(
		[
			'/\r+/',
			'/\n+/'
		], PHP_EOL, $output);

		/* general */

		$output = str_replace(
		[
			' {',
			'{ '
		], '{', $output);
		$output = str_replace(
		[
			' }',
			'} ',
		], '}', $output);
		$output = str_replace(
		[
			' :',
			': '
		], ':', $output);
		$output = str_replace(
		[
			' ;',
			'; '
		], ';', $output);
		$output = str_replace(
		[
			' ,',
			', '
		], ',', $output);

		/* scripts */

		if ($type === 'scripts')
		{
			$output = str_replace(
			[
				' (',
				'( '
			], '(', $output);
			$output = str_replace(
			[
				' )',
				') '
			], ')', $output);
			$output = str_replace(
			[
				' +',
				'+ '
			], '+', $output);
			$output = str_replace(
			[
				' -',
				'- '
			], '-', $output);
			$output = str_replace(
			[
				' =',
				'= '
			], '=', $output);
			$output = str_replace(
			[
				' >',
				'> '
			], '>', $output);
			$output = str_replace(
			[
				' <',
				'< '
			], '<', $output);
			$output = str_replace(
			[
				' ||',
				'|| '
			], '||', $output);
			$output = str_replace(
			[
				' &&',
				'&& '
			], '&&', $output);
		}

		/* trim output */

		$output = trim($output);
		return $output;
	}
}

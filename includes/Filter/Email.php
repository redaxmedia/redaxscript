<?php
namespace Redaxscript\Filter;

/**
 * children class to filter email
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Filter
 * @author Henry Ruhs
 */

class Email implements FilterInterface
{
	/**
	 * sanitize the email
	 *
	 * @since 2.2.0
	 *
	 * @param string $email target email address
	 *
	 * @return string
	 */

	public function sanitize($email = null)
	{
		return filter_var(strtolower($email), FILTER_SANITIZE_EMAIL);
	}
}
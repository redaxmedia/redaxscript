<?php
namespace Redaxscript\Filter;

use function filter_var;
use function strtolower;

/**
 * children class to filter the email
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
	 * @param string $email email address
	 *
	 * @return string
	 */

	public function sanitize(string $email = null) : string
	{
		return filter_var(strtolower($email), FILTER_SANITIZE_EMAIL);
	}
}
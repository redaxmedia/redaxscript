<?php
namespace Redaxscript\Validator;
use Redaxscript_Validator_Interface;

/**
 * children class to validate alias
 *
 * @since 2.2.0
 *
 * @category Redaxscript
 * @package Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Alias implements Redaxscript_Validator_Interface
{
	/**
	 * general validate mode
	 *
	 * @const integer
	 */

	const MODE_GENERAL = 0;

	/**
	 * default validate mode
	 *
	 * @const integer
	 */

	const MODE_DEFAULT = 1;

	/**
	 * array of default alias
	 *
	 * @var array
	 */

	protected $defaultArray = array(
		'admin',
		'loader',
		'login',
		'logout',
		'password_reset',
		'scripts',
		'styles',
		'registration',
		'reminder'
	);

	/**
	 * validate the alias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias alias for routing
	 * @param string $mode switch between general and default validation
	 *
	 * @return integer
	 */

	public function validate($alias = null, $mode = 0)
	{
		$output = Redaxscript_Validator_Interface::VALIDATION_FAIL;

		/* validate general alias */

		if ($mode === self::MODE_GENERAL)
		{
			if (preg_match('/[^a-z0-9_]/i', $alias) || is_numeric($alias))
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
		}

		/* validate default alias */

		else if ($mode === self::MODE_DEFAULT)
		{
			if (in_array($alias, $this->defaultArray))
			{
				$output = Redaxscript_Validator_Interface::VALIDATION_OK;
			}
		}
		return $output;
	}
}

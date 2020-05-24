<?php
namespace Redaxscript\Bootstrap;

use Redaxscript\Db;
use Redaxscript\Model;
use function date_default_timezone_set;
use function error_reporting;
use function ini_set;

/**
 * children class to boot the config
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Bootstrap
 * @author Henry Ruhs
 */

class Config extends BootstrapAbstract
{
	/**
	 * automate run
	 *
	 * @since 3.3.0
	 */

	public function autorun() : void
	{
		$settingModel = new Model\Setting();

		/* set as needed */

		if (Db::getStatus() === 2)
		{
			ini_set('default_charset', $settingModel->get('charset'));
			date_default_timezone_set($settingModel->get('zone'));
		}
		if (error_reporting() === 0)
		{
			ini_set('display_startup_errors', 0);
			ini_set('display_errors', 0);
		}
		ini_set('include_path', 'includes');
		ini_set('mbstring.substitute_character', 0);
		ini_set('session.use_trans_sid', 0);
		ini_set('url_rewriter.tags', 0);
	}
}

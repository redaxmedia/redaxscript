<?php
namespace Redaxscript\Admin\Template;

use Redaxscript\Template\Tag as BaseTag;

/**
 * parent class to provide admin template tags
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class Tag extends BaseTag
{
	/**
	 * panel
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function panel()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('admin_panel_list');
		// @codeCoverageIgnoreEnd
	}

	/**
	 * notification
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */

	public static function notification()
	{
		// @codeCoverageIgnoreStart
		return self::_migrate('admin_notification');
		// @codeCoverageIgnoreEnd
	}
}

<?php

/**
 * Migrate
 *
 * Provides wrapper functions for new objects which are called as functions
 * by template files.
 *
 * @since 2.1.0
 *
 * @category Migrate
 * @package Redaxscript
 * @author Gary Aylward
 */

/**
 * breadcrumb
 *
 * Wrapper function for Breadcrumb class for backwards compatibility
 *
 * @since 2.1.0
 *
 * @category Migrate
 * @package Redaxscript
 * @author Gary Aylward
 */
function breadcrumb()
{
	$breadcrumb = new Redaxscript_Breadcrumb;
	echo $breadcrumb->displayBreadcrumb();
}

?>
<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content categories view.
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogViewCategories extends JViewCategories
{
	/**
	 * Language key for default page heading
	 *
	 * @var    string
	 * @since  3.3.1
	 */
	protected $pageHeading = 'COM_PROJECTLOG_DEFAULT_PAGE_TITLE';

	/**
	 * @var    string  The name of the extension for the category
	 * @since  3.3.1
	 */
	protected $extension = 'com_projectlog';
}

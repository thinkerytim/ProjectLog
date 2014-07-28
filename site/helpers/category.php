<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Project Component Category Tree
 *
 * @package     Joomla.Site
 * @subpackage  com_projectlog
 * @since       1.6
 */
class ProjectlogCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table']       = '#__projectlog_projects';
		$options['extension']   = 'com_projectlog';
		$options['statefield']  = 'published';
		parent::__construct($options);
	}
}

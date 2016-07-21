<?php
/**
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Projectlog Component Category Tree
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogCategories extends JCategories
{
	/**
     * Constructor
     * 
     * @param   array   $options   Array of configuration options
     * 
     * @return  ProjectlogCategories
     * @see     JCategories
     * @since   3.3.1
     */
    public function __construct($options = array())
	{
		$options['table']       = '#__projectlog_projects';
		$options['extension']   = 'com_projectlog';
		$options['statefield']  = 'published';
		parent::__construct($options);
	}
}

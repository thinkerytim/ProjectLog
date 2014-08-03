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
 * HTML View class for the Project component
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
class ProjectlogViewCategory extends JViewCategoryfeed
{
	/**
	 * @var    string  The name of the view to link individual items to
	 * @since  3.3.1
	 */
	protected $viewName = 'project';

	/**
	 * Method to reconcile non standard names from components to usage in this class.
	 * Typically overriden in the component feed view class.
	 *
	 * @param   object  $item  The item for a feed, an element of the $items array.
	 *
	 * @return  void
	 *
	 * @since   3.3.1
	 */
	protected function reconcileNames($item)
	{
		parent::reconcileNames($item);

		$item->description = $item->misc;
	}
}

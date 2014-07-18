<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Project component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 * @since       1.6
 */
class ProjectlogHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PROJECTLOG_SUBMENU_PROJECTS'),
			'index.php?option=com_projectlog&view=projects',
			$vName == 'projects'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_PROJECTLOG_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_projectlog',
			$vName == 'categories'
		);
	}
    
    public static function getActions($component = 'com_projectlog', $section = '', $id = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete',
            'projectlog.createlog', 'projectlog.deletelog', 'projectlog.editlog', 'projectlog.editlog.state',
            'projectlog.editlog.own'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, 'com_projectlog.'.$section, $id));
		}

		return $result;
	}

}

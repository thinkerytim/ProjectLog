<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

class ProjectlogHelper
{
	public static function addSubmenu($vName)
	{
		$canDo	= ProjectlogHelper::getActions();

        JHtmlSidebar::addEntry(
            JText::_( 'COM_PROJECTLOG_PROJECTS' ),
            'index.php?option=com_projectlog&view=projects',
            $vName == 'projects'
        );
        
        JHtmlSidebar::addEntry(
            JText::_( 'COM_PROJECTLOG_CATEGORIES' ),
            'index.php?option=com_projectlog&view=categories',
            $vName == 'categories'
        );

        JHtmlSidebar::addEntry(
            JText::_( 'COM_PROJECTLOG_LOGS' ),
            'index.php?option=com_projectlog&view=logs',
            $vName == 'logs'
        );
        
        JHtmlSidebar::addEntry(
            JText::_( 'COM_PROJECTLOG_DOCS' ),
            'index.php?option=com_projectlog&view=docs',
            $vName == 'docs'
        );
        
        JHtmlSidebar::addEntry(
            JText::_( 'COM_PROJECTLOG_GROUPS' ),
            'index.php?option=com_projectlog&view=groups',
            $vName == 'groups'
        );

        if($canDo->get('core.admin')){
            JHtmlSidebar::addEntry(
                JText::_( 'JTOOLBAR_EDIT_CSS' ),
                'index.php?option=com_projectlog&view=editcss&layout=edit',
                $vName == 'editcss'
            );
        }        
	}

    public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, 'com_projectlog'));
		}

		return $result;
	}
}
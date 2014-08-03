<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Projectlog component helper.
 *
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 * @since       3.3.1
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
	 * @since   3.3.1
	 */
	public static function addSubmenu($vName)
	{
        JHtmlSidebar::addEntry(
			JText::_('COM_PROJECTLOG_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_projectlog',
			$vName == 'categories'
		);
        
        JHtmlSidebar::addEntry(
			JText::_('COM_PROJECTLOG_SUBMENU_PROJECTS'),
			'index.php?option=com_projectlog&view=projects',
			$vName == 'projects'
		);
        
        JHtmlSidebar::addEntry(
			JText::_('COM_PROJECTLOG_SUBMENU_LOGS'),
			'index.php?option=com_projectlog&view=logs',
			$vName == 'logs'
		);
        
        JHtmlSidebar::addEntry(
			JText::_('COM_PROJECTLOG_SUBMENU_DOCS'),
			'index.php?option=com_projectlog&view=docs',
			$vName == 'docs'
		);
	}
    
    /**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   string   $component  The component name.
	 * @param   string   $section    The access section name.
	 * @param   integer  $id         The item ID.
	 *
	 * @return  JObject
	 *
	 * @since   3.3.1
	 */    
    public static function getActions($component = 'com_projectlog', $section = '', $id = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete',
            'projectlog.createlog', 'projectlog.deletelog', 'projectlog.editlog', 'projectlog.editlog.state',
            'projectlog.editlog.own', 'projectlog.createdoc', 'projectlog.deletedoc', 'projectlog.editdoc',
            'projectlog.editdoc.state', 'projectlog.editdoc.own'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $component.'.'.$section.'.'.$id));
		}

		return $result;
	}
    
    /**
	 * Gets current running version of extension from manifest
	 *
	 * @return  String
	 *
	 * @since   3.3.1
	 */
    public static function _getversion()
	{
		return self::getParam();
	}
	
    /**
	 * Build extension footer with current product version
	 *
	 * @since   3.3.1
	 */
	public static function footer( )
	{		
		$version = ProjectlogHelper::_getversion();
        $footer_display = '<p class="center small"><a href="http://www.thethinkery.net" target="_blank">Project Log v.'.$version.' by The Thinkery LLC</a></p>';

        echo $footer_display;
	}
    
    /**
	 * Return a field from the extension manifest
	 *
	 * @param   string      $ext_name      The extension name.
	 * @param   string      $ext_typ       The extension type.
	 * @param   string      $field_name    The field name we want a value for
	 *
	 * @return  value       
	 *
	 * @since   3.3.1
	 */    
    public static function getParam( $ext_name = 'com_projectlog', $ext_type = 'component', $field_name = 'version' ) 
    {
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true);        
        $query->select('manifest_cache')
                ->from('#__extensions')
                ->where('name = '.$db->Quote($ext_name))
                ->where('type='.$db->Quote($ext_type));
        
        $db->setQuery($query);
        $manifest = json_decode( $db->loadResult(), true );
        
        return $manifest[$field_name];
    }
    
    /**
	 * Build the admin toolbar to dispaly above admin list views
	 *
	 * @since   3.3.1
	 */
    public static function buildAdminToolbar()
    {
        $user       = JFactory::getUser();

        JPluginHelper::importPlugin('projectlog');
        $dispatcher = JDispatcher::getInstance();       

        echo '
            <div class="pull-left">
                '.JHTML::_('image', 'administrator/components/com_projectlog/assets/images/projectlog_logo.png', 'ProjectLog :: By The Thinkery' ).'
            </div>';
            $dispatcher->trigger( 'onAfterRenderPlAdmin', array( &$user ) );
        echo '<div class="clearfix"></div>'; 
        echo '<hr />';
    }
}

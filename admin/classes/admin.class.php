<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */
 
defined('_JEXEC') or die('Restricted access');

class projectlogAdmin 
{

	public static function _getversion()
	{
		return self::getParam();
	}
	
	public static function footer( )
	{		
		$version = projectlogAdmin::_getversion();
        $footer_display = '<p class="center small"><a href="http://www.thethinkery.net" target="_blank">Project Log v.'.$version.' by The Thinkery LLC</a></p>';

        echo $footer_display;
	}
    
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
    
    public static function buildAdminToolbar()
    {
        $user       = JFactory::getUser();

        JPluginHelper::importPlugin('projectlog');
        $dispatcher = JDispatcher::getInstance();       

        echo '
            <div class="pull-left">
                '.JHTML::_('image', 'administrator/components/com_projectlog/assets/images/projectlog_admin_logo.png', 'ProjectLog :: By The Thinkery' ).'
            </div>';
            $dispatcher->trigger( 'onAfterRenderPlAdmin', array( &$user ) );
        echo '<div class="clearfix"></div>'; 
        echo '<hr />';
    }
}

?>
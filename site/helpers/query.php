<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class projectlogHelperQuery extends JObject
{
    function userAccess($action,$gid){
        $settings = & JComponentHelper::getParams( 'com_projectlog' );
        $db = JFactory::getDBO();
        switch($action){
            case 'basic_access':
                $result = ($settings->get('basic_access') <= $gid) ? true : false;
            break;
            case 'log_access':
                $result = ($settings->get('log_access') <= $gid) ? true : false;
            break;
            case 'doc_access':
                $result = ($settings->get('doc_access') <= $gid) ? true : false;
            break;
            case 'pedit_access':
                $result = ($settings->get('pedit_access') <= $gid) ? true : false;
            break;
            case 'ledit_access':
                $result = ($settings->get('ledit_access') <= $gid) ? true : false;
            break;
            case 'dedit_access':
                $result = ($settings->get('dedit_access') <= $gid) ? true : false;
            break;
            default:
                $result = false;
            break;
        }
        return $result;
    }

    function isGroupMember($group, $user){
        $db = JFactory::getDBO();
        $query = 'SELECT id FROM #__projectlog_groups_mid WHERE group_id = '.$group.' AND user_id = '.$user;
        $db->setQuery($query);
        if($db->loadResult()){
            return true;
        }else{
            return false;
        }
    }
}
?>
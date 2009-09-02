<?php
/**
 * @version 1.5.1 2009-03-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C)  2009 the Thinkery
 * @license GNU/GPL
 * @link http://www.thethinkery.net
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');
   
class ProjectlogViewHelper extends JView
{
    function getUsername( $userid )
    {
        $db        = JFactory::getDBO();
        $query     = "SELECT name FROM #__users WHERE id = " . $userid;
        $db->setQuery($query);
        $username  = $db->loadResult();
        return $username;
    }
	
	function userDetails( $userid )
    {
        $db          = JFactory::getDBO();
        $query       = "SELECT * FROM #__contact_details WHERE user_id = " . $userid;
        $db->setQuery($query);
        $userdetails = $db->loadObject();
        return $userdetails;
    }
}

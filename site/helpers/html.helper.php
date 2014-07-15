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

class projectlogHTML {
	
	function buildNoResults($accent_color, $wrapper = null){
		$html = '';
        if( $wrapper ) $html .= '<table class="rctable">';
		$html .= '<tr>
					 <td colspan="2" align="center">
						<div class="rc_noresults" style="border-color: ' . $accent_color . ';">
							<img src="administrator'.DS.'components'.DS.'com_projectlog'.DS.'assets'.DS.'images'.DS.'projectlog1.jpg" alt="' . JText::_('NO RECORDS') . '" /><br />
							' . JText::_('NO RECORDS TEXT') . '
						</div>
				    </td>
				 </tr>';
        if( $wrapper ) $html .= '</table>';
		
		return $html;
	}
	
	function buildThinkeryFooter($accent_color){
		$html = '';
		$html .= '<div class="rc_project_footer">
						'. JText::_('PROJECT LOG FOOTER'). ' ' . projectlogAdmin::_getversion(). ' ' . JText::_('BY') . ' <a href="http://www.thethinkery.net" target="_blank" style="color: ' . $accent_color . ';">theThinkery.net</a>
				  </div>';
		return $html;
	}
	
	function sentence_case($string) {
		$sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		$new_string = '';
		foreach ($sentences as $key => $sentence) {
			$new_string .= ($key & 1) == 0?
            ucfirst(strtolower(trim($sentence))) :
            $sentence.' ';
		}
		return trim($new_string);
	}
	
	function getItemid( $view ){
		//get item id for agent listings page
		$database = JFactory::getDBO();
		$query = "SELECT id FROM #__menu WHERE link LIKE '%index.php?option=com_projectlog&view=" . $view . "' AND published = 1";
        $database->setQuery($query);
		$Itemidresult = $database->loadResult();
		return $Itemidresult;
	}
    
    function statusSelect($tag,$attrib,$sel=null){
        $stats = array();
        $stats[] = JHTML::_('select.option', '', JText::_( 'SELECT' ));
        $stats[] = JHTML::_('select.option', JText::_( 'IN PROGRESS' ), JText::_( 'IN PROGRESS' ) );
        $stats[] = JHTML::_('select.option', JText::_( 'ON HOLD' ), JText::_( 'ON HOLD' ) );
        $stats[] = JHTML::_('select.option', JText::_( 'COMPLETE' ), JText::_( 'COMPLETE' ) );
        return JHTML::_('select.genericlist', $stats, $tag, $attrib, 'value', 'text', $sel );
    }


    function groupSelect($tag,$attrib,$sel=null){
		$database = JFactory::getDBO();
		$groups = array();
		$groups[] = JHTML::_('select.option', '', JText::_('GROUP'));
		$database->setQuery( "SELECT DISTINCT(id) AS 'value', name AS 'text' FROM #__projectlog_groups ORDER BY name ASC" );

		$groups = array_merge( $groups, $database->loadObjectList() );

		return  JHTML::_('select.genericlist',$groups,$tag,$attrib,'value','text',$sel);
	}

    function catSelect($tag,$attrib,$sel=null){
		$database = JFactory::getDBO();
		$cats = array();
		$cats[] = JHTML::_('select.option', '', JText::_('CATEGORY'));
		$database->setQuery( "SELECT DISTINCT(id) AS 'value', title AS 'text' FROM #__projectlog_categories ORDER BY title ASC" );

		$cats = array_merge( $cats, $database->loadObjectList() );

		return  JHTML::_('select.genericlist',$cats,$tag,$attrib,'value','text',$sel);
	}

    function projectSelect($tag,$attrib,$sel=null){
		$database = JFactory::getDBO();
		$projects = array();
		$projects[] = JHTML::_('select.option', '', JText::_('PROJECT'));
		$database->setQuery( "SELECT DISTINCT(id) AS 'value', title AS 'text' FROM #__projectlog_projects WHERE published = 1 ORDER BY title ASC" );

		$projects = array_merge( $projects, $database->loadObjectList() );

		return  JHTML::_('select.genericlist',$projects,$tag,$attrib,'value','text',$sel);
	}

    function getGroupName($group_id){
        $database = JFactory::getDBO();
        $database->setQuery( "SELECT name FROM #__projectlog_groups WHERE id = ".(int)$group_id );
        return $database->loadResult();
    }

    function getGroupCount($group_id){
        $database = JFactory::getDBO();
        $database->setQuery( "SELECT COUNT(id) FROM #__projectlog_groups_mid WHERE group_id = ".(int)$group_id );
        return $database->loadResult();
    }

    function getCatName($cat_id){
        $database = JFactory::getDBO();
        $database->setQuery( "SELECT title FROM #__projectlog_categories WHERE id = ".(int)$cat_id );
        return $database->loadResult();
    }

    function getCatCount($group_id){
        $database = JFactory::getDBO();
        $database->setQuery( "SELECT COUNT(id) FROM #__projectlog_projects WHERE group_access = ".(int)$group_id );
        return $database->loadResult();
    }

    function getProjectName($project_id){
        $database = JFactory::getDBO();
        $database->setQuery( "SELECT title FROM #__projectlog_projects WHERE id = ".(int)$project_id );
        return $database->loadResult();
    }

    function getUserName($user_id){
        $database = JFactory::getDBO();
        $database->setQuery( "SELECT username FROM #__users WHERE id = ".(int)$user_id );
        return $database->loadResult();
    }

	function userDetails( $user_id )
    {
        $database = JFactory::getDBO();
        $database->setQuery( "SELECT * FROM #__contact_details WHERE user_id = " . (int)$user_id );
        return $database->loadObject();
    }
    
    function notifyAdmin($type, $user, $project){
		global $mainframe;
        jimport( 'joomla.mail.helper' );
        $mode         = 1;
		$date         = date( 'M d Y' );
        $ipaddress    = $_SERVER['REMOTE_ADDR'];
        $uname        = ($user) ? $user->name : 'N/A';
        $admin_email  = $mainframe->getCfg('mailfrom');

		if($type){
            switch($type){
                case 'project':
                    $subject      = $mainframe->getCfg('sitename').' '.JText::_('NEW PROJECT SUBJECT');
                    $add_type     = sprintf(JText::_('NEW PROJECT EMAIL'), $mainframe->getCfg('sitename'));
                break;
                case 'log':
                    $subject      = $mainframe->getCfg('sitename').' '.JText::_('NEW LOG SUBJECT');
                    $add_type     = sprintf(JText::_('NEW LOG EMAIL'), $mainframe->getCfg('sitename'));
                break;
                case 'doc':
                    $subject      = $mainframe->getCfg('sitename').' '.JText::_('NEW DOC SUBJECT');
                    $add_type     = sprintf(JText::_('NEW DOC EMAIL'), $mainframe->getCfg('sitename'));
                break;
            }

            $body = '<p>'.$add_type . '</p>
                     <p><strong>' . JText::_('PROJECT') . ':</strong><br />' . $project . '</p>
                     <p><strong>' . JText::_('CREATED BY') . ':</strong><br />' . $uname . '</p>
                     <p>
                     <span style="font-size: 10px; color: #999;">
                        ' . JText::_('GENERATED BY PROJECTLOG') . ' ' . $date . '.<br />
                        IP: ' . $ipaddress . '
                     </span>
                     </p>';
            JUtility::sendMail($admin_email, $mainframe->getCfg('fromname'), $admin_email, $subject, $body, $mode, $cc, $bcc, $attachment, $from_email, $from_name);
        }
    }
}
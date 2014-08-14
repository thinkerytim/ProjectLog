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
 * Projectlog Html Helper
 *
 * @package     Projectlog.site
 * @subpackage  com_projectlog
 * @since       3.3.1
 */
abstract class ProjectlogHtml
{
    /**
	 * Method to truncate a string and add a tail to indicate there's more to read
	 *
	 * @param   string  $text   The text to truncate
	 * @param   int     $length Character length to reduce the string to
     * @param   string  $tail   Optional tailing indicator
     * @param   boolean $strip_tags True to strip html tags, false to leave html
	 *
	 * @return  string   The truncated string
	 * @since  3.3.1
	 */    
    public static function snippet($text, $length = 200, $tail = "(...)", $strip_tags = true)
    {
       $text = trim($text);
       $text = ($strip_tags) ? strip_tags($text) : $text;
       $txtl = strlen($text);
       if($txtl > $length) {
           for ($i = 1; $text[$length-$i] != " "; $i++) 
           {
               if ($i == $length) {
                   return substr($text, 0, $length) . $tail;
               }
           }
           $text = substr($text, 0, $length-$i+1) . $tail;
       }
	   // strip out curly bracket plugin text if it exists
	   $text = preg_replace( "/{([^:}]*):?([^}]*)}/", '', $text );
	   $text = str_replace( '  ', ' ', $text );
	   
       return $text;
    }
    
    /**
	 * Method to get a gravatar image from user email
	 *
	 * @param   string  $email  Email address
	 * @param   string  $mm     
     * @param   string  $size   Image size
	 *
	 * @return  array   Array of image url and actual image html
	 * @since  3.3.1
	 */
    public static function getGravatar($email, $default = 'mm', $size = 35)
	{
		$gravatar = array();
        // Get gravatar Image 
        // https://fr.gravatar.com/site/implement/images/php/
        $gravatar['url']    = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . $default . "&s=" . $size;
        $gravatar['image']  = JHtml::_('image', $gravatar['url'], JText::_('COM_PROJECTLOG_USER'));
        return $gravatar;
	}
    
    /**
	 * Method to build the Thinkery footer
	 *
	 * @return  string   footer html
	 * @since  3.3.1
	 */    
    public static function buildThinkeryFooter()
    {
        require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/projectlog.php';
        $plversion = projectlogHelper::_getversion();
        return '<p class="small pagination-centered pl-footer">'.sprintf(JText::_('COM_PROJECTLOG_FOOTER'), Jhtml::_('link', 'http://thethinkery.net', 'The Thinkery LLC', array('target' => '_blank')), $plversion).'</p>';
    }
    
    /**
	 * Method to return project name from id
     * 
     * @param   int     $project_id     The project id
	 *
	 * @return  mixed   False if no result, project name string if successful
	 * @since  3.3.1
	 */ 
    public static function getProjectName($project_id)
    {
        // If no project id, return false
        if(!$project_id) return false;
        
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true);
        $query->select('name')
                ->from('#__projectlog_projects')
                ->where('id = '.(int)$project_id);
        
        $db->setQuery($query);
        return $db->loadResult();
    }
    
    /**
	 * Method to return basic project information like project name, category, manager info
     * 
     * @param   int     $project_id     The project id
	 *
	 * @return  mixed   False if no project id or query fails, object if successful
	 * @since  3.3.1
	 */
    public static function getBasicProjectInfo($project_id)
    {
        // If no project id, return false
        if(!$project_id) return false;
        
        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true);
        $query->select('p.id AS project_id, p.name AS project_name, p.catid AS project_cat')
                ->from('#__projectlog_projects AS p')
        
                ->select('u.*, u.email AS manager_email, u.name AS manager_name')
                ->join('LEFT', '#__users AS u ON u.id = p.manager');
        
        $query->where('p.id = '.(int)$project_id);
        
        $db->setQuery($query, 0, 1);
        return $db->loadObject();
    }
    
    /**
	 * Method to send notification emails to admin/manager
     * 
     * @param   int     $project_id     The project id
     * @param   string  $type           The type of notification (log, doc, or project)
	 *
	 * @return  mixed   False if no project id or query fails, db row object if successful
	 * @since  3.3.1
	 */
    public static function notifyAdmin($project_id, $type = 'project', $needs_approval = false, $new = false)
    {
		// If no project id or an invalid notification type, return false
        if(!$project_id or !in_array($type, array('log','doc','project','approval'))) return false;
        
        $app    = JFactory::getApplication();
        $user   = JFactory::getUser();
        $plparams = JComponentHelper::getParams('com_projectlog');
        
		// Set some basic vars for date, ip address and user
        $full_date      = JHTML::_('date','now',JText::_('DATE_FORMAT_LC2'));
        $ipaddress      = $_SERVER['REMOTE_ADDR'];
        $user_name      = ($user) ? $user->get('name') : 'N/A';
        
        // Get config mail values and site name
        $admin_from     = $app->getCfg('fromname');
        $admin_email    = $app->getCfg('mailfrom');
        $site_name      = $app->getCfg('sitename');
        
        // Get basic project info to populate email
        $project_info   = projectlogHtml::getBasicProjectInfo($project_id);
        $project_name   = $project_info->project_name;
        $manager_name   = $project_info->manager_name;
        $manager_email  = $project_info->manager_email;
        
        // Add recipients
        $recipients = array();
        
        // Notify levels: 0:administrator, 1:manager, 2:both administrator and manager
        $notify_level = $plparams->get('notify_level',0);
        // If notify level is not set to only manager, add the admin email to recipient array
        if($notify_level != 1){
            $recipients[] = $admin_email;
        }
        
        // If notify level is not set to only administrator and the manager email is not the same as the
        // administrator email, add the manager email to recipient array
        if(in_array($notify_level, array(1,2))){
            if($manager_email && $manager_email != $admin_email){
                $recipients[] = $manager_email;
            }
        }   
        
        // Build the project path to include in email
        $uri            = JUri::getInstance();
		$base           = $uri->toString(array('scheme', 'host', 'port'));
		$project_path   = $base . JRoute::_(ProjectlogHelperRoute::getProjectRoute($project_info->project_id, $project_info->project_cat), false);

		// Switch the notification type subject and body content and send email
        if($type){
            switch($type){
                case 'project':
                    $subject    = ($new) ? sprintf(JText::_('COM_PROJECTLOG_NOTIFICATION_SUBJECT'), 'project', $site_name) : sprintf(JText::_('COM_PROJECTLOG_UPDATE_NOTIFICATION_SUBJECT'), 'project', $site_name);
                    $body       = sprintf(JText::_('COM_PROJECTLOG_NOTIFICATION_BODY'), 'project', $site_name, $user_name, $project_name, $manager_name, $project_path);
                break;
                case 'log':
                    $subject    = sprintf(JText::_('COM_PROJECTLOG_NOTIFICATION_SUBJECT'), 'project log', $site_name);
                    $body       = sprintf(JText::_('COM_PROJECTLOG_NOTIFICATION_BODY'), 'project log', $site_name, $user_name, $project_name, $manager_name, $project_path);
                break;
                case 'doc':
                    $subject    = sprintf(JText::_('COM_PROJECTLOG_NOTIFICATION_SUBJECT'), 'project document', $site_name);
                    $body       = sprintf(JText::_('COM_PROJECTLOG_NOTIFICATION_BODY'), 'project document', $site_name, $user_name, $project_name, $manager_name, $project_path);
                break;
                case 'approval':
                    $subject    = sprintf(JText::_('COM_PROJECTLOG_APPROVAL_SUBJECT'), $site_name);
                    $body       = sprintf(JText::_('COM_PROJECTLOG_APPROVAL_BODY'), $project_name, $project_path);
                break;
            }
            
            if($needs_approval){
                $config         = JFactory::getConfig();
                $secret         = $config->get('secret');
                $hash           = md5($project_id.$secret);
                $approve_link   = $base . JRoute::_(ProjectlogHelperRoute::getCategoryRoute($project_info->project_cat), false).'&task=project.approveProject&project_id='.(int)$project_id.'&token='.$hash;
                
                $body .= sprintf(JText::_('COM_PROJECTLOG_PROJECT_APPROVAL_REQUIRED'), $approve_link);
            }

            $body .= sprintf(JText::_('COM_PROJECTLOG_GENERATED_BY'), $full_date, $ipaddress);
            
            $mail = JFactory::getMailer();
            $mail->addRecipient( $recipients );
            $mail->addReplyTo(array($admin_email, $admin_from));
            $mail->setSender( array( $admin_email, $admin_from ));
            $mail->setSubject( $subject );
            $mail->setBody( $body );    
            $mail->Send();
        }
    }   
}

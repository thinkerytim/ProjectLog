<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.log.log');

class ProjectlogControllerAjax extends JControllerLegacy
{
	protected $text_prefix = 'COM_IPROPERTY';
    
    public function resetHits()
    {
        // Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        $prop_id = JRequest::getInt('prop_id');
        
        $db     = JFactory::getDbo();
        $query  = 'UPDATE #__iproperty SET hits = 0 WHERE id = '.(int)$prop_id.' LIMIT 1';
        $db->setQuery($query);
        
        if($db->Query()){
            echo JText::_('COM_IPROPERTY_COUNTER_RESET');
        }else{
            return false;
        }
    }
    
	public function ajaxAutocomplete()
    {
		/**
		* Get json encoded list of DB values
		* @param string $field BD field to filter
		* @param string $token Joomla token
		* @return json_encoded list of values
		*/ 
		// Check for request forgeries
		JSession::checkToken('get') or die( 'Invalid Token');
		
		$field		= JRequest::getString('field');
		
		$db         = JFactory::getDbo();
		
		$query 		= $db->getQuery(true);
		$query->select('DISTINCT '.$db->quoteName($field))
			->from('#__projectlog_projects')
			->groupby($db->quoteName($field));

		$db->setQuery($query);
		$data = $db->loadColumn();               
		
		echo json_encode($data);
	}
    
    public function addLog()
    {
        /*if($_POST['act'] == 'add-com'):
            $name = htmlentities($name);
            $email = htmlentities($email);
            $comment = htmlentities($comment);

            // Connect to the database
            include('../config.php'); 

            // Get gravatar Image 
            // https://fr.gravatar.com/site/implement/images/php/
            $default = "mm";
            $size = 35;
            $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . $default . "&s=" . $size;

            if(strlen($name) <= '1'){ $name = 'Guest';}
            //insert the comment in the database
            mysql_query("INSERT INTO comments (name, email, comment, id_post)VALUES( '$name', '$email', '$comment', '$id_post')");
            if(!mysql_errno()){
        */
        
        $field		= JRequest::getString('log_title');
        
        echo 
            '<div class="cmt-cnt">
                <img src="test" alt="" />
                <div class="thecom">
                    <h5>test</h5><span  class="com-dt">dtate</span>
                    <br/>
                    <p>log here</p>
                </div>
            </div>';
    }
}
?>

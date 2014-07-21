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
	protected $text_prefix = 'COM_PROJECTLOG';
    
    public function resetHits()
    {
        // Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        $prop_id = JRequest::getInt('prop_id');
        
        $db     = JFactory::getDbo();
        $query  = 'UPDATE #__iproperty SET hits = 0 WHERE id = '.(int)$prop_id.' LIMIT 1';
        $db->setQuery($query);
        
        if($db->Query()){
            echo JText::_($this->text_prefix.'_COUNTER_RESET');
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
        // Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        
        $data       = JRequest::get('post');
        $user       = JFactory::getUser();
        $currdate   = JFactory::getDate()->toSql();
        
        $logtitle   = htmlentities($data['title']);
        $logdesc    = htmlentities($data['log']);
        $logdate    = JFactory::getDate();
        $project_id = (int)$data['project_id'];
        
        $grav_url = projectlogHtml::getGravatar($user->email);       

        $db = JFactory::getDbo();
        
        $newlog = new stdClass();
        $newlog->title = $logtitle;
        $newlog->description = $logdesc;
        $newlog->project_id = (int)$project_id;
        $newlog->created = $currdate;
        $newlog->created_by = $user->id;
        $newlog->published = 1;
        
        try
        {          
            if($result = $db->insertObject('#__projectlog_logs', $newlog)){
                $new_log_id = $db->insertid();
                $success_msg = 
                  '<div class="log-cnt" id="logid-'.$new_log_id.'">
                      <img src="'.$grav_url.'" alt="" />
                      <div class="thelog">
                          <h5>'.$newlog->title.'</h5>
                          <br/>
                          <p>'.$newlog->description.'</p>
                          <p data-utime="1371248446" class="small log-dt">'.$user->name.' - '.JHtml::date($newlog->created,JText::_('DATE_FORMAT_LC2')).'</p>
                      </div>
                  </div>';
                echo new JResponseJson($success_msg);
                return;
            }
            echo new JResponseJson($result);    
            return;
        }
        catch(Exception $e)
        {
          echo new JResponseJson($e);
        }
    }
    
    public function deleteLog()
    {
        // Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        
        $data       = JRequest::get('post');
        $log_id     = (int)$data['log_id'];
        
        $db         = JFactory::getDbo(); 
        $query      = $db->getQuery(true);       
        
        try{
            $query->delete($db->quoteName('#__projectlog_logs'));
            $query->where($db->quoteName('id').' = '.$log_id);

            $db->setQuery($query);
            $result = $db->query();
            
            if($result){
                echo new JResponseJson($log_id);
            }
            else
            {
                echo new JResponseJson($result);
            }
        }
        catch(Exception $e)
        {
            echo new JResponseJson($e);
        }            
    }
}
?>

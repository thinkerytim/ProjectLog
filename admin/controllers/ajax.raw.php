<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.controller');
jimport('joomla.log.log');

class ProjectlogControllerAjax extends JControllerLegacy
{    
	/**
     * Get json encoded list of DB values
     * 
     * @param string $field BD field to filter     * 
     * @param string $token Joomla token
     * 
     * @return json_encoded list of values
     */
    public function ajaxAutocomplete()
    {		 
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
    
    /**
     * Add a new log via the project logs tab
     * 
     * @return JResponseJson result of ajax request
     */
    public function addLog()
    {        
        // Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        
        $data       = JRequest::get('post');
        $user       = JFactory::getUser();
        $model      = $this->getModel('Log');
        $currdate   = JFactory::getDate()->toSql();
        
        $data['associations']   = array();
        $data['published']      = 1;
        $data['created_by']     = $user->get('id');
        
        try
        {          
            $result = false;
            if($model->save($data)){
                $success_msg = 
                  '<div class="log-cnt">
                      <img src="'.projectlogHtml::getGravatar($user->get('id')).'" alt="" />
                      <div class="thelog">
                          <h5>'.$data['title'].'</h5>
                          <br/>
                          <p>'.$data['description'].'</p>
                          <p data-utime="1371248446" class="small log-dt">'.$user->get('name').' - '.JHtml::date($currdate, JText::_('DATE_FORMAT_LC2')).'</p>
                      </div>
                  </div>';
                echo new JResponseJson($success_msg);
                return;
            }
            echo new JResponseJson($result, $model->getError());    
            return;
        }
        catch(Exception $e)
        {
          echo new JResponseJson($e);
        }
    }
    
    /**
     * Delete a new log via the project logs tab
     * 
     * @return JResponseJson result of ajax request
     */    
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

<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2016 The Thinkery, LLC. All rights reserved.
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
     * @param string $field DB field to filter     * 
     * @param string $token Joomla token
     * 
     * @return json_encoded list of values
     */
    public function autoComplete()
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
		
		echo new JResponseJson($data);
	}
    
    /**
     * Add a new log via the project logs tab
     * 
     * @return JResponseJson result of ajax request
     */
    public function addLog()
    {        
        // Check for request forgeries
        JSession::checkToken('get') or die( 'Invalid Token');
        $app = JFactory::getApplication();
        
        $data       = JRequest::get('post');
        $user       = JFactory::getUser();
        
        $clientmodel    = (JFactory::getApplication()->getName() == 'site') ? 'Logform' : 'Log';
        $model          = $this->getModel($clientmodel);

        $currdate   = JFactory::getDate()->toSql();
        $gravatar   = projectlogHtml::getGravatar($user->get('email'));
        
        $data['associations']   = array();
        $data['published']      = 1;
        $data['created_by']     = $user->get('id');
        $data['language']       = JFactory::getLanguage()->getTag();
        if(!$data['language']) $data['language'] = '*';
        
        try
        {          
            $result = false;
            if($model->save($data)){
                $new_log = 
                  '<div class="plitem-cnt">
                      '.$gravatar["image"].'
                      <div class="theplitem">
                          <h5>'.$data['title'].'</h5>
                          <br/>
                          <p>'.$data['description'].'</p>
                          <p data-utime="1371248446" class="small plitem-dt">
                            '.$user->get('name').' - '.JHtml::date($currdate, JText::_('DATE_FORMAT_LC2')).'
                          </p>
                      </div>
                  </div>';
                //$app->enqueueMessage(JText::_('COM_PROJECTLOG_SUCCESS'));
                echo new JResponseJson($new_log);
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
     * Delete a log via the project logs tab
     * 
     * @return JResponseJson result of ajax request
     */    
    public function deleteLog()
    {
        // Check for request forgeries
        JSession::checkToken('get') or die( 'Invalid Token');
        
        $data       = JRequest::get('post');
        $log_id     = (int)$data['log_id'];
        
        $clientmodel    = (JFactory::getApplication()->getName() == 'site') ? 'Logform' : 'Log';
        $model          = $this->getModel($clientmodel);       
        
        try{
            $result = false;
            if($model->delete($log_id)){
                echo new JResponseJson($log_id);
            }
            else
            {
                echo new JResponseJson($result, $model->getError());
            }
        }
        catch(Exception $e)
        {
            echo new JResponseJson($e);
        }            
    }
    
    /**
     * Delete a document via the project docs tab
     * 
     * @return JResponseJson result of ajax request
     */    
    public function deleteDoc()
    {
        // Check for request forgeries
        JSession::checkToken('get') or die( 'Invalid Token');
        
        $data       = JRequest::get('post');
        $doc_id     = (int)$data['doc_id'];
        
        $clientmodel    = (JFactory::getApplication()->getName() == 'site') ? 'Docform' : 'Doc';
        $model          = $this->getModel($clientmodel);       
        
        try{
            $result = false;
            if($model->delete($doc_id)){
                echo new JResponseJson($doc_id);
            }
            else
            {
                echo new JResponseJson($result, $model->getError());
            }
        }
        catch(Exception $e)
        {
            echo new JResponseJson($e);
        }            
    }
}
?>

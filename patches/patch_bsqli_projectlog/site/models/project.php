<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');
jimport('joomla.application.component.model');

class ProjectlogModelProject extends JModel
{
	var $_id      = null;
	var $_project = null;
	var $_data    = null;
    var $_logs    = null;
    var $_docs    = null;

	function __construct()
	{
		parent::__construct();
        global $option;

        $mainframe =& JFactory::getApplication() ;
		$this->setId( JRequest::getInt('id', '0' ));
	}

	function setId($id)
	{
		$this->_id = $id;
	}

	function &getData()
	{
		if ($this->loadData())
		{

		}
		else  $this->_initData();
        return $this->_data ;
	}

	function loadData()
	{
		if (empty($this->_data))
		{
            $query = 'SELECT *'
				   . ' FROM #__projectlog_projects'
				   . ' WHERE id = '.(int)$this->_id;

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}
		return true;
	}

    function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$project = new stdClass();
			$project->id			= 0;
            $project->category      = null;
            $project->group_access  = null;
			$project->release_id    = null;
            $project->job_id        = null;
            $project->task_id       = null;
            $project->workorder_id  = null;
            $project->title         = null;
            $project->description   = null;
            $project->release_date  = null;
            $project->contract_from = null;
            $project->contract_to   = null;
            $project->location_gen  = null;
            $project->location_spec = null;
            $project->manager       = null;
            $project->chief         = null;
            $project->technicians   = null;
            $project->deployment_from = null;
            $project->deployment_to = null;
            $project->onsite        = null;
            $project->projecttype    = null;
            $project->client        = null;
            $project->status        = null;
            $project->approved      = null;
            $project->published     = 1;

            $this->_data			           = $project;
			return (boolean) $this->_data;
        }
    }    
	
	function getLogs(){
		$query = 'SELECT * FROM #__projectlog_logs WHERE project_id = ' . (int)$this->_id . ' AND published = 1 ORDER BY date DESC';
        $this->_db->setQuery($query);
        $this->_logs = $this->_db->loadObjectlist();
        return $this->_logs;
	}

    function getLog($id){
		$db = JFactory::getDBO();
        $query = 'SELECT * FROM #__projectlog_logs WHERE id = ' . (int)$id;
        $db->setQuery($query);
        $logitem = $db->loadObject();
        return $logitem;
	}
	
	function getDocs(){
		$query = 'SELECT * FROM #__projectlog_docs WHERE project_id = ' . (int)$this->_id . ' ORDER BY date DESC';
        $this->_db->setQuery($query);
        $this->_docs = $this->_db->loadObjectlist();
        return $this->_docs;
	}

    function saveLog($data)
	{
        $settings   = & JComponentHelper::getParams( 'com_projectlog' );
		$user		= & JFactory::getUser();

		$row  =& $this->getTable('projectlog_logs', '');

		if (!$row->bind($data)) {
			$this->setError($row->getError());
			return false;
		}

		$row->id = (int) $row->id;        
        if(!$data['id']){
            $row->loggedby = $data['userid'];
            $row->date = date('Y-m-d H:i:s');
        }else{
            $row->modified = date('Y-m-d H:i:s');
            $row->modified_by = $data['userid'];
        }

		$nullDate	= $this->_db->getNullDate();

		// Make sure the data is valid
		if (!$row->check($settings)) {
			$this->setError($row->getError());
			return false;
		}

		// Store it in the db
		if (!$row->store()) {
			$this->setError($row->getError());
			return false;
		}

        if( ($settings->get('notify_admin_log') == 1) && !$data['id'] ){
            projectlogHTML::notifyAdmin('log', $user, projectlogHTML::getProjectName($row->project_id));
        }

		return $row->id;
	}

    function deleteLog($id)
    {
        $query = 'DELETE FROM #__projectlog_logs WHERE id = '.(int) $id.' LIMIT 1';
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($row->getError());
			return false;
        }
        return true;
    }
	
	function saveDoc($data) {
        $settings   = & JComponentHelper::getParams( 'com_projectlog' );
		$user		= & JFactory::getUser();
		$row  =& $this->getTable('projectlog_docs', '');

		if (!$row->bind($data)) {
			$this->setError($row->getError());
			return false;
		}
		$row->id = (int) $row->id;
        $row->date = date('Y-m-d');
        $row->submittedby = $user->get('id');

		// Make sure the data is valid
		if (!$row->check($settings)) {
			$this->setError($row->getError());
			return false;
		}

		// Store it in the db
		if (!$row->store()) {
			$this->setError($row->getError());
			return false;
		}

        if( $settings->get('notify_admin_doc') == 1 ){
            projectlogHTML::notifyAdmin('doc', $user, projectlogHTML::getProjectName($row->project_id));
        }

		return $row->id;
	}
	
	function deleteDoc($id)
	{
		if ($id)
		{
            $query = 'SELECT path FROM #__projectlog_docs WHERE id = ' . (int)$id;
            $this->_db->setQuery($query);
            $file = $this->_db->loadResult();
            $this->deleteFile($file);

			$query = 'DELETE FROM #__projectlog_docs WHERE id ='. (int)$id;
			$this->_db->setQuery( $query );

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}else{
            $this->setError(JText::_('NO DOCS SELECTED'));
            return false;
        }
		return true;
	}

    function saveFile($file){
        //Import filesystem libraries. Perhaps not necessary, but does not hurt
        jimport('joomla.filesystem.file');
        $settings   = & JComponentHelper::getParams( 'com_projectlog' );
        $allowed = explode(',',trim($settings->get('doc_types')));

        //Clean up filename to get rid of strange characters like spaces etc
        $filename = JFile::makeSafe($file['name']);

        //Set up the source and destination of the file
        $src = $file['tmp_name'];
        $dest = JPATH_SITE.DS.'media'.DS.'com_projectlog'.DS.'docs'.DS.$filename;
        $ext = strtolower(JFile::getExt($filename) );

        if(file_exists($dest)){
            return JText::_('FILE EXISTS').' - '. $filename;
        }

        //Verify this is an acceptable doc file
        if (in_array($ext,$allowed)) {
           if ( JFile::upload($src, $dest) ) {
              //continue
           } else {
              return JText::_('FILE NOT UPLOADED');
           }
        } else {
           return sprintf(JText::_('WRONG FILE TYPE'),$ext);
        }
    }

    function deleteFile($file){
        jimport('joomla.filesystem.file');
        $path = JPATH_SITE.DS.'media'.DS.'com_projectlog'.DS.'docs'.DS;
        JFile::delete($path.$file);
    }
}

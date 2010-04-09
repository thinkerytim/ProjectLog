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
jimport('joomla.application.component.model');

class projectlogModelproject extends JModel
{
    var $_id = null;
    var $_data = null;

    function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

    function setId($id)
	{
		$this->_id	    = $id;
		$this->_data	= null;
	}

    function &getData()
	{
		if ($this->_loadData())
		{

		}
		else  $this->_initData();

		return $this->_data;
	}

    function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT *'
				   . ' FROM #__projectlog_projects'
				   . ' WHERE id = '.$this->_id;

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
		return true;
	}
	
	function store($data)
	{
		$settings   = & JComponentHelper::getParams( 'com_projectlog' );
		$user		= & JFactory::getUser();
		$row        =& $this->getTable('projectlog_projects', '');

		if (!$row->bind($data)) {
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}		
		$row->id = (int) $row->id;

		// Make sure the data is valid
		if (!$row->check($settings)) {
			$this->setError($row->getError());
			return false;
		}

		// Store it in the db
		if (!$row->store()) {
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}
		return $row->id;
	}	
}

?>
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

class projectlogModellog extends JModel
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
				   . ' FROM #__projectlog_logs'
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
			$log = new stdClass();
			$log->id				   = 0;
			$log->project_id           = null;
            $log->title 			   = null;
            $log->description	       = null;
            $log->date                 = null;
            $log->loggedby             = null;
            $log->modified             = null;
            $log->modified_by          = null;
            $log->published            = 1;
			
			$this->_data			   = $log;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function store($data)
	{
		$settings   = & JComponentHelper::getParams( 'com_projectlog' );
		$user		= & JFactory::getUser();

		$row  =& $this->getTable('projectlog_logs', '');

		if (!$row->bind($data)) {
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}

		jimport('joomla.filesystem.file');
		
		$row->id = (int) $row->id;

		$nullDate	= $this->_db->getNullDate();

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
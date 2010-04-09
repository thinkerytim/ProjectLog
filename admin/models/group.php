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

class projectlogModelgroup extends JModel
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
				   . ' FROM #__projectlog_groups'
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
			$group = new stdClass();
			$group->id				   = 0;
			$group->name               = null;
			
			$this->_data			           = $group;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function store($data)
	{
		$settings   = & JComponentHelper::getParams( 'com_projectlog' );
		$user		= & JFactory::getUser();

		$row  =& $this->getTable('projectlog_groups', '');

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

        $group_id = $row->id;
        $users = split(",",$data['gusers']);
        $this->_bindUsers($group_id, $users);

		return $row->id;
	}

    function _bindUsers($group_id, $users){
        $settings   = & JComponentHelper::getParams( 'com_projectlog' );
        $query = "DELETE FROM #__projectlog_groups_mid WHERE group_id = ".$group_id;
        $this->_db->setQuery($query);
        $this->_db->Query();

        // bind new images to object
        for($i=0; $i < count($users); $i++) {

            $gmid = & $this->getTable('projectlog_groups_mid', '');
            $gmid->group_id 			= $group_id;
            $gmid->user_id				= $users[$i];

            // Make sure the data is valid
            if (!$gmid->check($settings)) {
                $this->setError($gmid->getError());
                return false;
            }

            // Store it in the db
            if (!$gmid->store()) {
                JError::raiseError(500, $this->_db->getErrorMsg() );
                return false;
            }
        }
        return true;
    }

    function getUsers(){
        $query = 'SELECT id, name, username FROM #__users AS u WHERE block = 0 ORDER BY name ASC';
		$this->_db->setQuery($query);
        $users = $this->_db->loadObjectList();
        return $users;
    }

    function getGusers(){
        $query = 'SELECT u.id, u.name, u.username FROM #__users AS u'
                .' LEFT JOIN #__projectlog_groups_mid AS gm ON gm.user_id = u.id'
                .' WHERE gm.group_id = '.$this->_id
                .' ORDER BY u.name ASC';
		$this->_db->setQuery($query);
        $users = $this->_db->loadObjectList();
        return $users;
    }

}

?>
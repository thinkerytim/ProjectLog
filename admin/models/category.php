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

class projectlogModelcategory extends JModel
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
				   . ' FROM #__projectlog_categories'
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
			$category = new stdClass();
			$category->id			    = 0;
			$category->title			= null;
			
			$this->_data			    = $category;
			return (boolean) $this->_data;
		}
		return true;
	}
	
	function store($data)
	{
		$projectlogsettings = & JComponentHelper::getParams( 'com_projectlog' );

		if(is_array($data['title'])){
            for($i = 0; $i < sizeof($data['title']); $i++){
                if($data['title'][$i] != ''){
                    $row  =& $this->getTable('projectlog_categories', '');

                    if (!$row->bind($data)) {
                        JError::raiseError(500, $this->_db->getErrorMsg() );
                        return false;
                    }

                    $row->id = (int) $row->id;
                    $row->title = $data['title'][$i];

                    // Make sure the data is valid
                    if (!$row->check($projectlogsettings)) {
                        $this->setError($row->getError());
                        return false;
                    }

                    // Store it in the db
                    if (!$row->store()) {
                        JError::raiseError(500, $this->_db->getErrorMsg() );
                        return false;
                    }
                }
            }
        }else{
            if($data['title'] != NULL){
                $row  =& $this->getTable('projectlog_categories', '');

                if (!$row->bind($data)) {
                    JError::raiseError(500, $this->_db->getErrorMsg() );
                    return false;
                }

                $row->id = (int) $row->id;

                // Make sure the data is valid
                if (!$row->check($projectlogsettings)) {
                    $this->setError($row->getError());
                    return false;
                }

                // Store it in the db
                if (!$row->store()) {
                    JError::raiseError(500, $this->_db->getErrorMsg() );
                    return false;
                }
            }else{
                return false;
            }
        }

		return true;
	}
	
}

?>
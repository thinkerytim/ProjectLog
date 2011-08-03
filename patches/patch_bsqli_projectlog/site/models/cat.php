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
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class projectlogModelCat extends JModel
{
	var $_data = null;
	var $_id = null;
	var $_where = null;
	var $_total = null;

	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;
		$settings = & $mainframe->getParams('com_projectlog');
        // Get the pagination request variables
        $limit       	= $mainframe->getUserStateFromRequest($option.'.cat.limit', 'limit', $settings->def('perpage', 0), 'int');
        $limitstart		= JRequest::getVar('limitstart', 0, '', 'int');

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter_order', JRequest::getCmd('filter_order', 'p.release_date'));
		$this->setState('filter_order_dir', JRequest::getCmd('filter_order_Dir', 'DESC'));

		// Set id for project type
		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setId($id);
	}

	function setId($id)
	{
		$this->_id	    = $id;
		$this->_data	= null;
	}

	function getData()
	{
	    global $mainframe, $option;
		$settings = & $mainframe->getParams('com_projectlog');

		if (empty($this->_data))
		{
            $query = "SELECT * FROM #__projectlog_categories WHERE id = " . (int)$this->_id;
            
            $this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
		}
		return $this->_data;
	}

    function getProject($id){
		$db = JFactory::getDBO();
        $query = 'SELECT * FROM #__projectlog_projects WHERE id = ' . (int)$id;
        $db->setQuery($query);
        $pitem = $db->loadObject();
        return $pitem;
	}

    function getProjects()
	{
	    global $mainframe, $option;
		$debug = 0;

		if (empty($this->_projects))
		{
			$query = $this->_buildQuery();
			$this->_projects = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_projects;
	}

	function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return $this->_total;
	}

	function getPagination()
	{
	  // Lets load the content if it doesn't already exist
	  if (empty($this->_pagination))
	  {
		 jimport('joomla.html.pagination');
		 $this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
	  }
	  return $this->_pagination;
	}

    function _buildQuery(){
        $where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
        $user       = &JFactory::getUser();

        $query = 'SELECT p.*, p.id AS id, p.title AS title, p.description as description, c.title as cattitle'
                .' FROM #__projectlog_projects AS p'
                .' LEFT JOIN #__projectlog_groups AS g ON p.group_access = g.id'
                .' LEFT JOIN #__projectlog_groups_mid AS gm ON gm.group_id = g.id'
                .' LEFT JOIN #__projectlog_categories AS c ON c.id = p.category'
                .' WHERE p.published = 1'
                .' AND p.approved = 1';

                //if user is not admin, make sure they have group access if any applied
                if($user->get('gid') < 24){
                    $query .= ' AND'
                             .' CASE'
                             .' WHEN p.group_access != 0 THEN'
                             .' '.$user->id.' IN (SELECT user_id FROM #__projectlog_groups_mid WHERE group_id = gm.group_id)'
                             .' WHEN p.group_access = 0 THEN'
                             .' 1 = 1'
                             .' END';
                }
        $query .= $where
                .' GROUP BY p.id'
                . $orderby;

        return $query;

    }

	function _buildContentWhere()
	{
		global $mainframe, $option;
        $db = &JFactory::getDBO();
        
        $where = array();
        $where = ' AND p.category = ' . (int)$this->_id;

        $filter 			= $mainframe->getUserStateFromRequest( $option.'.cat.filter', 'filter', '', 'int' );
        $search 			= $mainframe->getUserStateFromRequest( $option.'.cat.search', 'search', '', 'string' );

        $search	= urldecode($search);
        $search	= JString::strtolower($search);

		if ($search && $filter == 1) {
			$where .= ' AND LOWER(p.title) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
		}elseif( $search && $filter == 2 ){
            $where .= ' AND LOWER(p.release_id) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false );
        }

		return $where;
	}

    function _buildContentOrderBy()
	{
		$filter_order		= $this->getState('filter_order');
		$filter_order_dir	= $this->getState('filter_order_dir');

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_dir;

		return $orderby;
	}

    function projectSitestatus( $id, $status = 0 )
    {
        $db =& JFactory::getDBO();
        if (!$id ) {
            $action = $status ? 'onsite' : 'offsite';
            echo "<script>alert('Select an item to set $action'); window.history.go(-1);</script>";
            exit;
        }
        $db->setQuery( "UPDATE #__projectlog_projects SET onsite = ".(int)$status." WHERE id =". (int)$id );
        if (!$db->query()) {
            echo "<script>alert('".JText::_('ERROR SAVING')."'); window.history.go(-1);</script>";
            exit();
        }
    }

    function changeStatus( $id )
    {
        global $my, $mainframe, $itemid;
        $db =& JFactory::getDBO();
        $query = "SELECT status FROM #__projectlog_projects WHERE id = " . (int)$id . " LIMIT 1";
        $db->setQuery($query);
        $oldstatus = $db->loadResult();

        switch($oldstatus)
        {
            case JText::_('IN PROGRESS'):
                $newstatus = JText::_('ON HOLD');
            break;

            case JText::_('ON HOLD'):
                $newstatus = JText::_('COMPLETE');
            break;

            case JText::_('COMPLETE'):
                $newstatus = JText::_('IN PROGRESS');
            break;

            default:
                $newstatus = JText::_('IN PROGRESS');
            break;
        }

        if (!$id ) {
            $action = $status ? 'onsite' : 'offsite';
            echo "<script>alert('".JText::_('SELECT ITEM') . " " . $action ."'); window.history.go(-1);</script>";
            exit;
        }
        $db->setQuery( "UPDATE #__projectlog_projects SET status = ".$db->Quote($newstatus)." WHERE id =". (int)$id );
        if (!$db->query()) {
            echo "<script>alert('".JText::_('ERROR SAVING')."'); window.history.go(-1);</script>";
            exit();
        }
    }

    function saveProject($post){
        global $mainframe;
        $settings = & $mainframe->getParams('com_projectlog');
		$user		= & JFactory::getUser();
		$row        =& $this->getTable('projectlog_projects', '');

		if (!$row->bind($post)) {
			JError::raiseError(500, $this->_db->getErrorMsg() );
			return false;
		}
		$row->id = (int) $row->id;
        $row->created_by = (int) $user->get('id');
        $row->approved = ($settings->get('approval') && !$post['id']) ? 0 : 1;
        $row->published = ($settings->get('approval') && !$post['id']) ? 0 : 1;

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

        if( ($settings->get('notify_admin_project') == 1) && !$post['id'] ){
            projectlogHTML::notifyAdmin('project', $user, $row->title);
        }
		return $row->id;
    }

    function deleteProject($id)
	{
		$result = false;
        $query = 'DELETE FROM #__projectlog_projects'
               . ' WHERE id ='.(int)$id;

        $this->_db->setQuery( $query );

        if(!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $this->deleteLogs($id);
        $this->deleteDocs($id);

		return true;
	}

    function deleteLogs($id){
        $query = 'DELETE FROM #__projectlog_logs WHERE project_id = '.(int)$id;
        $this->_db->setQuery( $query );
        $this->_db->query();
    }

    function deleteDocs($id){
        $query = 'SELECT path FROM #__projectlog_docs WHERE project_id = '.(int)$id;
        $this->_db->setQuery($query);
        $docs = $this->_db->loadObjectList();

        foreach($docs as $d){
            $this->deleteFile($d->path);
        }

        $query = 'DELETE FROM #__projectlog_docs WHERE project_id ='.(int)$id;
        $this->_db->setQuery( $query );
        $this->_db->query();
    }

    function deleteFile($file){
        jimport('joomla.filesystem.file');
        $path = JPATH_SITE.DS.'media'.DS.'com_projectlog'.DS.'docs'.DS;
        JFile::delete($path.$file);
    }
}

?>
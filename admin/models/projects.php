<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class projectlogModelprojects extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		$limit		= $mainframe->getUserStateFromRequest( $option.'.projects.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest( $option.'.projects.limitstart', 'limitstart', 0, 'int' );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}
	 
	function getData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	function getTotal()
	{
		// Lets load the total nr if it doesn't already exist
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

	function _buildQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT  * FROM #__projectlog_projects'
				 . $where
				 . $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.projects.filter_order', 'filter_order', 'release_date', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.projects.filter_order_Dir', 'filter_order_Dir', 'DESC', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		return $orderby;
	}

	function _buildContentWhere()
	{
		global $mainframe, $option;

		$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.projects.filter_state', 'filter_state', '', 'word' );
		$filter 			= $mainframe->getUserStateFromRequest( $option.'.projects.filter', 'filter', '', 'int' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.projects.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );
        $category 			= $mainframe->getUserStateFromRequest( $option.'.projects.category', 'category', '', 'string' );
        $group  			= $mainframe->getUserStateFromRequest( $option.'.projects.group_access', 'group_access', '', 'string' );

		$where = array();

        if ($filter_state) {
			if ($filter_state == 'P') {
				$where[] = 'published = 1';
			} else if ($filter_state == 'U') {
				$where[] = 'published = 0';
			} else {
				$where[] = 'published >= 0';
			}
		} else {
			$where[] = 'published >= 0';
		}

		if ($search && $filter == 1) {
			$where[] = ' LOWER(`title`) LIKE \'%'.$search.'%\' ';
		}elseif( $search && $filter == 2 ){
            $where[] = ' LOWER(`release_id`) LIKE \'%'.$search.'%\' ';
        }elseif( $search && $filter == 3 ){
            $where[] = ' LOWER(`job_id`) LIKE \'%'.$search.'%\' ';
        }elseif( $search && $filter == 4 ){
            $where[] = ' LOWER(`task_id`) LIKE \'%'.$search.'%\' ';
        }elseif( $search && $filter == 5 ){
            $where[] = ' LOWER(`workorder_id`) LIKE \'%'.$search.'%\' ';
        }elseif( $search && $filter == 6 ){
            $where[] = ' LOWER(`description`) LIKE \'%'.$search.'%\' ';
        }

        if($group) $where[] = ' group_access = ' . $group;
        if($category) $where[] = ' category = ' . $category;

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();
		$userid = (int) $user->get('id');

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__projectlog_projects'
				   . ' SET published = '. (int) $publish
				   . ' WHERE id IN ('. $cids .')';
                
			$this->_db->setQuery( $query );

			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
	}

	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__projectlog_projects'
				   . ' WHERE id IN ('. $cids .')';

			$this->_db->setQuery( $query );            

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

            $this->deleteLogs($cids);
            $this->deleteDocs($cids);
		}

		return true;
	}

    function deleteLogs($cids){
        $query = 'DELETE FROM #__projectlog_logs WHERE project_id IN ('. $cids .')';
        $this->_db->setQuery( $query );
        $this->_db->query();
    }

    function deleteDocs($cids){
        $query = 'SELECT path FROM #__projectlog_docs WHERE project_id IN ('.$cids.')';
        $this->_db->setQuery($query);
        $docs = $this->_db->loadObjectList();

        foreach($docs as $d){
            $this->deleteFile($d->path);
        }

        $query = 'DELETE FROM #__projectlog_docs WHERE project_id IN ('. $cids .')';
        $this->_db->setQuery( $query );
        $this->_db->query();
    }

    function deleteFile($file){
        jimport('joomla.filesystem.file');
        $path = JPATH_SITE.DS.'media'.DS.'com_projectlog'.DS.'docs'.DS;
        JFile::delete($path.$file);
    }    

    function changeStatus( $id )
    {
        global $mainframe;
        $query = "SELECT status FROM #__projectlog_projects WHERE id = " . $id . " LIMIT 1";
        $this->_db->setQuery($query);
        $oldstatus = $this->_db->loadResult();

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

        $this->_db->setQuery( "UPDATE #__projectlog_projects SET status = '$newstatus' WHERE id =". $id );
        if(!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }

    function approveProject($cid = array(), $approve = 1)
	{
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__projectlog_projects'
				   . ' SET approved = '. (int) $approve
				   . ' WHERE id IN ('. $cids .')';

			$this->_db->setQuery( $query );

			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
        return true;
	}
}//Class end
?>
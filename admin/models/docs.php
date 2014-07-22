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

class projectlogModeldocs extends JModel
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	
	function __construct()
	{
		parent::__construct();

		global $mainframe, $option;

		$limit		= $mainframe->getUserStateFromRequest( $option.'.docs.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest( $option.'.docs.limitstart', 'limitstart', 0, 'int' );

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

		$query = 'SELECT * FROM #__projectlog_docs'
				 . $where
				 . $orderby;

		return $query;
	}

	function _buildContentOrderBy()
	{
		global $mainframe, $option;

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.docs.filter_order', 'filter_order', 'path', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.docs.filter_order_Dir', 'filter_order_Dir', 'ASC', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
		return $orderby;
	}

	function _buildContentWhere()
	{
		global $mainframe, $option;

		$filter 			= $mainframe->getUserStateFromRequest( $option.'.docs.filter', 'filter', '', 'int' );
		$search 			= $mainframe->getUserStateFromRequest( $option.'.docs.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );
        $project_id			= $mainframe->getUserStateFromRequest( $option.'.docs.project_id', 'project_id', '', 'int' );

		$where = array();

		if ($search && $filter == 1) {
			$where[] = ' LOWER(`name`) LIKE \'%'.$search.'%\' ';
		}elseif( $search && $filter == 2 ){
            $where[] = ' LOWER(`path`) LIKE \'%'.$search.'%\' ';
        }

        if($project_id) $where[] = 'project_id = ' . $project_id;

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	function delete($cid = array())
	{
		if (count( $cid ))
		{
			foreach($cid AS $c){
                $query = 'SELECT path FROM #__projectlog_docs WHERE id = ' . $c;
                $this->_db->setQuery($query);
                $file = $this->_db->loadResult();
                $this->deleteFile($file);
            }

            $cids = implode( ',', $cid );
			$query = 'DELETE FROM #__projectlog_docs WHERE id IN ('. $cids .')';
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

    function deleteFile($file){
        jimport('joomla.filesystem.file');
        $path = JPATH_SITE.DS.'media'.DS.'com_projectlog'.DS.'docs'.DS;
        JFile::delete($path.$file);
    }
}//Class end
?>
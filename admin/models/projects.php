<?php
/**
 * @version 3.3.1 2014-07-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 - 2014 the Thinkery LLC. All rights reserved.
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

class ProjectlogModelProjects extends JModelList
{
	var $_data = null;
	var $_total = null;
	var $_pagination = null;
	
	function __construct()
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'p.id',
				'category', 'p.category',
                'group_access', 'p.group_access',
                'release_id', 'p.release_id',
                'job_id', 'p.job_id',
                'title', 'p.title',
				'published', 'p.published'
			);
		}

		parent::__construct($config);
	}
    
    protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.state');

		return parent::getStoreId($id);
	}

	public function getTable($type = 'Project', $prefix = 'ProjectlogTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		// List state information.
		parent::populateState();
	}

    protected function getListQuery()
	{
		// Initialise variables.
		$db         = $this->getDbo();
		$query      = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
                'p.*,'.
				'p.id as id,'.
                'p.title as title'   
			)
		);
		$query->from('`#__projectlog_projects` AS p');

        // Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = p.checked_out');

        // Join over the company
		//$query->select('c.name as company_title');
		//$query->join('LEFT', '`#__iproperty_companies` AS c ON c.id = a.company');
        
        // Join over the agent mid to get property count
		//$query->join('LEFT', '`#__iproperty_agentmid` AS am ON am.agent_id = a.id');

        // Join over user
        //$query->select('u.username as user_username, u.id as user_id, u.name as user_name');
		//$query->join('LEFT', '`#__users` AS u ON u.id = a.user_id');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('p.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(p.published IN (0, 1))');
		}

        // Filter by company.
		/*$companyId = $this->getState('filter.company_id');
		if ($companyId && is_numeric($companyId)) {
			$query->where('a.company = '.(int) $companyId);
		}*/

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('p.id = '.(int) substr($search, 3));
			}
			else {
				$search     = JString::strtolower($search);
                $search     = explode(' ', $search);
                $searchwhere   = array();
                if (is_array($search)){ //more than one search word
                    foreach ($search as $word){
                        $searchwhere[] = 'LOWER(p.title) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.description) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                    }
                } else {
                    $searchwhere[] = 'LOWER(p.title) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.description) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                }
                $query->where('('.implode( ' OR ', $searchwhere ).')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'release_date');
		$orderDirn	= $this->state->get('list.direction', 'DESC');
        $query->group('p.id');
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
	 
	/*function getData()
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

		$query = 'SELECT * FROM #__projectlog_projects'
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
        $path = JPATH_SITE.'/media/com_projectlog/docs'.DS;
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
	}*/
}//Class end
?>
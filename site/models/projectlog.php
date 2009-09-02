<?php
/**
 * @version 1.5.1 2009-03-15
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C)  2009 the Thinkery
 * @license GNU/GPL
 * @link http://www.thethinkery.net
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('No access');

jimport('joomla.application.component.model');

class ProjectlogModelProjectlog extends JModel
{
	var $_id = null;
	var $_project = null;
	var $_data = null;
    var $_total = null;
    var $_pagination = null;

	function __construct()
	{
		parent::__construct();
        global $option;

        $mainframe =& JFactory::getApplication() ;
         $config = JFactory::getConfig() ;

		$this->setId( JRequest::getInt('project_id', '0' ));

        // Get the pagination request variables
        $limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $config->getValue('config.list_limit'), 'int' );
        $limitstart = JRequest::getInt( 'limitstart', 0 );

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
	}

	function setId($id)
	{
		$this->_id = $id;
	}

	function &getData()
	{
		$this->loadData() ;
        if( $this->_pagination ) {
                echo 'here!';
            $data = array_slice( $this->_data, $this->getState('limitstart'), $this->getState('limit'));
            return $data ;
        } else {
            return $this->_data ;
        }
	}

	function loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
            $this->_data = array();
            $where = $this->getWhere();
            $orderby = $this->getOrderby();
            $limit = $this->getState('limit');
            $limitstart = $this->getState('limitstart');
            if( !$limit ) $limit = 99999;

			$query = 'SELECT p.* FROM #__projectlog_projects AS p'.
                    ' WHERE p.published = 1'.
                    $where .
                    $orderby .
                    ' LIMIT ' . $limitstart . ', ' . $limit;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObjectList();
			return $this->_data;
		}
		return array() ;
	}

	function &getProject($project_id)
	{
		if (empty($this->_project))
		{
            $db = &JFactory::getDBO();
            if( $project_id != null ) {
                $this->_project_id = (int)$project_id ;
            }
            
			$query = 'SELECT p.* FROM #__projectlog_projects AS p WHERE id = '.(int)$this->_project_id;

			$db->setQuery($query);
			$this->_project = $db->loadObject();

            if( $this->_project == null ) {
                return $this->_initProject();
            }

        }
		return $this->_project;
	}

    function getTotal()
    {
         $this->_data = array();
         $where = $this->getWhere() ;
         $orderby = $this->getOrderby() ;

         $query = 'SELECT p.* FROM #__projectlog_projects AS p'.
                ' WHERE p.published = 1'.
                $where .
                $orderby ;
         $this->_db->setQuery($query);
         $total = $this->_db->loadObjectList();
         $this->_total = count( $total) ;
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

    function getWhere() {
        global $mainframe;
        
        $where = '' ;
        $filter 			= $mainframe->getUserStateFromRequest( $option.'.filter', 'filter', '', 'int' );
        $search 			= $mainframe->getUserStateFromRequest( $option.'.search', 'search', '', 'string' );
		$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );

        if ($search && $filter == 1) {
			$where .= " AND p.title LIKE '%" . $search . "%'";
		}else if ($search && $filter == 2) {
			$where .= " AND p.release_id LIKE '%" . $search . "%'";
		}
        return $where ;
    }

    function getOrderby() {
        global $mainframe;

		$sort		= $mainframe->getUserStateFromRequest( $option.'.projectlog.filter_order', 'filter_order', 'p.release_date DESC', 'cmd' );
		$order	    = $mainframe->getUserStateFromRequest( $option.'.projectlog.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$orderby = ' ORDER BY ';

        if ($sort && $order)
		{
			$orderby .= $sort .' '. $order;
		}else{
            $orderby .= 'p.release_date DESC';
        }
        return $orderby;
    }

    function setLimit( $limit ) {
        if( $limit > 0 ) {
            $this->setState('limit', $limit);
            return true ;
        }
        return false ;
    }

    function _initProject() {
        if (empty($this->_project))
        {
            $project = new stdClass();
            $project->id            = 0;
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
            $project->surveytype    = null;
            $project->surveyor      = null;
            $project->status        = null;
            $project->published     = 1;
            $this->_project         = $project;
        }
        return $this->_project;
    }

    function saveProject($post)
	{
        $query = 'SELECT count(*)' .
                ' FROM #__projectlog_projects' .
                ' WHERE id = '.(int) $post['id'];
        $this->_db->setQuery($query);
        $project = $this->_db->loadResult();
        $date = date('Y-m-d H:i:s');

        $release_id       = $post['release_id'];
        $job_id           = $post['job_id'];
        $task_id          = $post['task_id'];
        $workorder_id     = $post['workorder_id'];
        $title            = $post['title'];
        $description      = $post['description'];
        $release_date     = $post['release_date'];
        $contract_from    = $post['contract_from'];
        $contract_to      = $post['contract_to'];
        $location_gen     = $post['location_gen'];
        $location_spec    = $post['location_spec'];
        $manager          = $post['manager'];
        $chief            = $post['chief'];
        $technicians      = $post['technicians'];
        $deployment_from  = $post['deployment_from'];
        $deployment_to    = $post['deployment_to'];
        $onsite           = $post['onsite'];
        $surveytype       = $post['surveytype'];
        $surveyor         = $post['surveyor'];
        $status           = $post['status'];
        $published        = 1;
        
        if (!$project)
        {
            // There are no ratings yet, so lets insert our rating
            $query = "INSERT INTO #__projectlog_projects"
                    . " ( release_id, job_id, task_id, workorder_id, title, description, release_date, contract_from,"
                    . " contract_to, location_gen, location_spec, manager, chief, technicians, deployment_from, deployment_to,"
                    . " onsite, surveytype, surveyor, status, published )"
                    . " VALUES"
                    . " ( '$release_id', '$job_id', '$task_id', '$workorder_id', '$title', '$description', '$release_date', '$contract_from',"
                    . " '$contract_to', '$location_gen', '$location_spec', '$manager', '$chief', '$technicians', '$deployment_from', '$deployment_to',"
                    . " '$onsite', '$surveytype', '$surveyor', '$status', $published )";
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                JError::raiseError( 500, $this->_db->stderr());
            }
            $msg = 'Project successfully entered!';
        }
        else
        {
            $query = "UPDATE #__projectlog_projects"
                    . " SET `release_id` = '$release_id', `job_id` = '$job_id', `task_id` = '$task_id', `workorder_id` = '$workorder_id', `title` = '$title', `description` = '$description',"
                    . " `release_date` = '$release_date', `contract_from` = '$contract_from', `contract_to` = '$contract_to', `location_gen` = '$location_gen', `location_spec` = '$location_spec',"
                    . " `manager` = '$manager', `chief` = '$chief', `technicians` = '$technicians', `deployment_from` = '$deployment_from', `deployment_to` = '$deployment_to',"
                    . " `onsite` = '$onsite', `surveytype` = '$surveytype', `surveyor` = '$surveyor', `status` = '$status'"
                    . " WHERE `id` = ".$post['id'];
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                JError::raiseError( 500, $this->_db->stderr());
            }
            $msg = 'Project successfully updated!';
        }
        return $msg;
	}

    function deleteProject($id)
    {
        $database =& JFactory::getDBO();
		$doc_path = "media/com_projectlog/docs";

        $query = 'DELETE FROM #__projectlog_projects' .
                 ' WHERE id = '.(int) $id.' LIMIT 1';
        $database->setQuery($query);
        if (!$database->query()) {
            JError::raiseError( 500, $this->_db->stderr());
        }

        $query = 'DELETE FROM #__projectlog_logs' .
                 ' WHERE project_id = '.(int) $id.' LIMIT 1';
        $database->setQuery($query);
        if (!$database->query()) {
            JError::raiseError( 500, $this->_db->stderr());
        }

        # Get the filename from the DB to delete
		$sql="SELECT path FROM #__projectlog_docs WHERE `project_id`='".(int) $id."'";
		$database->setQuery($sql);
		$old_docs = $database->loadObjectList();

		# Remove from database
		$sql="DELETE FROM #__projectlog_docs WHERE `project_id`='".(int) $id."'";
		$database->setQuery($sql);
		$database->query();

		# Delete the old document
		if (!empty($old_docs)) {
			foreach($old_docs AS $old_doc) {
				if (!empty($old_doc->path)) {
					if(!unlink(JPATH_SITE.DS.$doc_path.DS.$old_doc->path)) {
						echo "<script> alert('Error while deleting documents'); window.history.go(-1); </script>\n";
						exit();
					}
				}
			}
		} // End of Empty
        $msg = 'Project successfully deleted!';
        return $msg;
    }

    function projectSitestatus( $id, $status = 0 )
    {
        global $my, $mainframe, $itemid;
        $db =& JFactory::getDBO();
        if (!$id ) {
            $action = $status ? 'onsite' : 'offsite';
            echo "<script>alert('Select an item to set $action'); window.history.go(-1);</script>";
            exit;
        }
        $db->setQuery( "UPDATE #__projectlog_projects SET onsite = '$status' WHERE id =". $id );
        if (!$db->query()) {
            echo "<script>alert('An error has occurred while saving project. Please try again.'); window.history.go(-1);</script>";
            exit();
        }
    }

    function changeStatus( $id )
    {
        global $my, $mainframe, $itemid;
        $db =& JFactory::getDBO();
        $query = "SELECT status FROM #__projectlog_projects WHERE id = " . $id . " LIMIT 1";
        $db->setQuery($query);
        $oldstatus = $db->loadResult();

        switch($oldstatus)
        {
            case 'In Progress':
                $newstatus = 'On Hold';
            break;

            case 'On Hold':
                $newstatus = 'Complete';
            break;

            case 'Complete':
                $newstatus = 'In Progress';
            break;

            default:
                $newstatus = 'In Progress';
            break;
        }

        if (!$id ) {
            $action = $status ? 'onsite' : 'offsite';
            echo "<script>alert('Select an item to set $action'); window.history.go(-1);</script>";
            exit;
        }
        $db->setQuery( "UPDATE #__projectlog_projects SET status = '$newstatus' WHERE id =". $id );
        if (!$db->query()) {
            echo "<script>alert('An error has occurred while saving project. Please try again.'); window.history.go(-1);</script>";
            exit();
        }
    }
}

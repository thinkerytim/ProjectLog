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
		$this->setId( JRequest::getInt('project_id', '0' ));
	}

	function setId($id)
	{
		$this->_id = $id;
	}

	function &getData()
	{
		$this->loadData();
        return $this->_data ;
	}

	function loadData()
	{
		if (empty($this->_data))
		{
            $this->_data = array();
            $where = $this->getWhere() ;

			$query = 'SELECT p.* FROM #__projectlog_projects AS p'.
                    ' WHERE p.published = 1'.
                    $where .
                    $orderby ;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return $this->_data;
		}
		return array() ;
	}

    function getWhere() {
        $where = '' ;
		$where .= ' AND p.id = ' . $this->_id;
        return $where ;
    }
	
	function getLogs(){
		$query = 'SELECT * FROM #__projectlog_logs WHERE project_id = ' . $this->_id . ' ORDER BY date DESC';
        $this->_db->setQuery($query);
        $this->_logs = $this->_db->loadObjectlist();
        return $this->_logs;
	}

    function getLog($id){
		$db = JFactory::getDBO();
        $query = 'SELECT * FROM #__projectlog_logs WHERE id = ' . $id;
        $db->setQuery($query);
        $logitem = $db->loadObject();
        return $logitem;
	}
	
	function getDocs(){
		$query = 'SELECT * FROM #__projectlog_docs WHERE project_id = ' . $this->_id . ' ORDER BY date DESC';
        $this->_db->setQuery($query);
        $this->_docs = $this->_db->loadObjectlist();
        return $this->_docs;
	}

    function saveLog($post)
	{
        $query = 'SELECT *' .
                ' FROM #__projectlog_logs' .
                ' WHERE id = '.(int) $post['id'];
        $this->_db->setQuery($query);
        $log = $this->_db->loadObject();
        $date = date('Y-m-d H:i:s');

        if (!$log)
        {
            // There are no ratings yet, so lets insert our rating
            $query = 'INSERT INTO #__projectlog_logs ( project_id, title, description, date, loggedby )' .
                    ' VALUES ( '.(int) $post['project_id'].', \''.$post['title'].'\', \''.addslashes($post['description']).'\', \'' . $date . '\', '.$post['userid'].' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                JError::raiseError( 500, $this->_db->stderr());
            }
            $msg = 'Log successfully entered!';
        }
        else
        {
            $query = 'UPDATE #__projectlog_logs' .
                    ' SET title = \''.$post['title'].'\', description = \''.addslashes($post['description']).'\', modified = \'' . $date . '\', modified_by = '.$post['userid'] .
                    ' WHERE id = '.(int) $post['id'];
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                JError::raiseError( 500, $this->_db->stderr());
            }
            $msg = 'Log successfully updated!';
        }
        return $msg;
	}

    function deleteLog($id)
    {
        $query = 'DELETE FROM #__projectlog_logs' .
                 ' WHERE id = '.(int) $id.' LIMIT 1';
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            JError::raiseError( 500, $this->_db->stderr());
        }
        $msg = 'Log successfully deleted!';
    }
	
	function saveDoc($post) {
		$doc_path = "media/com_projectlog/docs";
		$database =& JFactory::getDBO();
	
		$project = JRequest::getInt('project_id');
		$option = JRequest::getVar('option',array(0),'default','array');
		$addeddocument = 0;
		
		# Does our directory allow writing for public?
		if ($_FILES['document']['tmp_name'] != '') {
			if(!is_writable(JPATH_SITE.DS.$doc_path)) { 
					echo "<script> alert('The document directory is not writable. Please chmod this directory to 755 before uploading new images: ".JPATH_SITE.DS.$doc_path.DS."'); window.history.go(-1); </script>\n";
					exit();
			}
		}
		
		if ($project == 0 || ($project != 0 && $_FILES['document']['tmp_name'] != '') ) {
			# Verify image is supplied 
			if(!isset($_FILES['document']) || !is_array($_FILES['document'])) {
				echo "<script> alert('Please make sure you have selected a document to upload.'); window.history.go(-1); </script>\n";
				exit();
			}
	
			# Verify the file is uploaded
			if (!is_uploaded_file($_FILES['document']['tmp_name'])) {
				echo "<script> alert('Please make sure you specified a valid document before uploading.'); window.history.go(-1); </script>\n";
				exit();
			} 
	
			$dname = $_FILES['document']['name'];
            # Verify this is an acceptable doc file (.doc,.odf,.pdf,.zip,.jpg,.jpeg,.gif,.dwg,.dgn,.tif,.xls,.ppt,.bmp)
			if ((strcasecmp(substr($dname,-4),".pdf")) && (strcasecmp(substr($dname,-4),".odf")) && (strcasecmp(substr($dname,-4),".doc")) && (strcasecmp(substr($dname,-4),".zip")) && (strcasecmp(substr($dname,-4),".jpg")) && (strcasecmp(substr($dname,-5),".jpeg")) && (strcasecmp(substr($dname,-4),".dwg")) && (strcasecmp(substr($dname,-4),".dgn")) && (strcasecmp(substr($dname,-4),".tif")) && (strcasecmp(substr($dname,-4),".xls")) && (strcasecmp(substr($dname,-4),".ppt")) && (strcasecmp(substr($dname,-4),".bmp")) && (strcasecmp(substr($dname,-4),".gif"))  ) {
				echo "<script> alert('Only files of type doc,odf,pdf,zip,jpg,jpeg,dwg,dgn,tif,xls,ppt,or bmp can be uploaded.'); window.history.go(-1); </script>\n";
				exit();
			}
	
			# No Duplicates!
			if (file_exists(JPATH_SITE.DS.$doc_path.DS.$_FILES['document']['name'])) {
				echo "<script> alert('There already exists a file with the same name. Please rename and upload again.'); window.history.go(-1); </script>\n";
				exit();
			} 
		} 
		
		if(copy ($_FILES['document']['tmp_name'], JPATH_SITE.DS.$doc_path.DS.$_FILES['document']['name'])) {
			$doc_name = $_FILES['document']['name'];
            $date = date('Y-m-d');
			$query = "INSERT INTO #__projectlog_docs (`path`,`project_id`,`name`,`date`,`submittedby`) VALUES ('" . $doc_name . "', '" . $project . "', '" . $post['name'] . "', '" . $date . "', '" . $post['userid'] . "')";
			$database->setQuery( $query );
			$database->query();
			$success = 1;		
		}	
		
		if($success == 1){
			$msg = "You were successful!";        
		}else{
			$msg = "Sorry, the document was not uploaded. Please make sure it is a doc,odf,pdf,zip,jpg,jpeg,dwg,dgn,tif,xls,ppt,or bmp filetype and try again.";
		}
		return $msg;
	}
	
	function deleteDoc($id)
    {
        $database =& JFactory::getDBO();
		$doc_path = "media/com_projectlog/docs";
		
		$addeddocument = 0;
		
		# Get the filename from the DB to delete
		$sql="SELECT path FROM #__projectlog_docs WHERE `id`='".$id."' LIMIT 1";
		$database->setQuery($sql);
		$old_docs = $database->loadObjectList();
	
		# Remove from database
		$sql="DELETE FROM #__projectlog_docs WHERE `id`='".$id."'";
		$database->setQuery($sql);
		$database->query();
		
		# Delete the old document
		if (!empty($old_docs)) {
			foreach($old_docs AS $old_doc) {
				if (!empty($old_doc->path)) {
					if(!unlink(JPATH_SITE.DS.$doc_path.DS.$old_doc->path)) {
						echo "<script> alert('Error while deleting document'); window.history.go(-1); </script>\n";
						exit();
					}
				}
			}
			$success = 1;
		} // End of Empty
	
		if($success == 1){
			$msg = "You were successful!";        
		}else{
			$msg = "Sorry, there was a problem deleting the document. Please try again.";
		}
		return $msg;
    }
}

<?php
/**
 * @version 1.5.3 2009-10-12
 * @package Joomla
 * @subpackage Project Log
 * @copyright (C) 2009 the Thinkery
 * @link http://thethinkery.net
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class projectlogControllerprojects extends projectlogController {

	function __construct()
	{		
		parent::__construct();
		
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'apply', 'save' );
	}
		
	function publish()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
        $cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO PUBLISH' ) );
		}

		$model = $this->getModel('projects');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_('ITEM PUBLISHED');

		$this->setRedirect( 'index.php?option=com_projectlog&view=projects', $msg );
	}
    
	function unpublish()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
        $cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO UNPUBLISH' ) );
		}

		$model = $this->getModel('projects');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_('ITEM UNPUBLISHED');

		$this->setRedirect( 'index.php?option=com_projectlog&view=projects', $msg );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$this->setRedirect( 'index.php?option=com_projectlog&view=projects' );
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
        $cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO DELETE' ) );
		}

		$model = $this->getModel('projects');
        $model->delete($cid);

		$total = count( $cid );
        $msg = $total . ' '.JText::_('ITEM DELETED');;

		$cache = &JFactory::getCache('com_projectlog');
		$cache->clean();

		$this->setRedirect( 'index.php?option=com_projectlog&view=projects', $msg );
	}

	function edit( )
	{
        JRequest::setVar( 'view', 'project' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}
    
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$task		= JRequest::getVar('task');
		$post = JRequest::get( 'post', JREQUEST_ALLOWRAW);
		$model = $this->getModel('project');
        
		if ($returnid = $model->store($post)) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_projectlog&controller=projects&view=project&hidemainmenu=1&cid[]='.$returnid;
					break;

				default :
					$link = 'index.php?option=com_projectlog&view=projects';
					break;
			}
			$msg	= JText::_( 'ITEM SAVED');
            $type   = 'message';

			$cache = &JFactory::getCache('com_projectlog');
			$cache->clean();

		} else {

			$msg 	= JText::_( 'ITEM NOT SAVED');
			$link = 'index.php?option=com_projectlog&view=projects';
            $type = 'notice';

		}
		$this->setRedirect( $link, $msg, $type );
 	}

    function edit_css( )
	{
        JRequest::setVar( 'css_file', 'projectlog.css' );
        JRequest::setVar( 'view', 'editcss' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}

    function approve()
	{
		// Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );
        $cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO APPROVE' ) );
		}

		$model = $this->getModel('projects');
		if(!$model->approveProject($cid, 1)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_('ITEM APPROVED');
        $link   = 'index.php?option=com_projectlog&view=projects';

		$cache = &JFactory::getCache('com_projectlog');
		$cache->clean();
        $this->setRedirect( $link, $msg );
	}

    function disapprove()
	{
		// Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );
        $cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO DISAPPROVE' ) );
		}

		$model = $this->getModel('projects');
		if(!$model->approveProject($cid, 0)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_('ITEM DISAPPROVED');
        $link   = 'index.php?option=com_projectlog&view=projects';

		$cache = &JFactory::getCache('com_projectlog');
		$cache->clean();
        $this->setRedirect( $link, $msg );
	}

    function changeStatus(){
        // Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );
        $cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO CHANGE' ) );
		}

		$model = $this->getModel('projects');
		if(!$model->changeStatus($cid[0])) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$msg 	= JText::_('STATUS CHANGED SUCCESS');
        $link   = 'index.php?option=com_projectlog&view=projects';

		$cache = &JFactory::getCache('com_projectlog');
		$cache->clean();
        $this->setRedirect( $link, $msg );
    }
}
?>

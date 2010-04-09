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

class projectlogControllercategories extends projectlogController {

	function __construct()
	{		
		parent::__construct();
		
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'apply', 'save' );
	}

	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		
        $id = JRequest::getVar('id');
        $model = $this->getModel('category');

		$this->setRedirect( 'index.php?option=com_projectlog&view=categories' );
	}

	function remove()
	{
		global $option;

		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO DELETE' ) );
		}

		$model = $this->getModel('categories');
		$msg = $model->delete($cid).' '.JText::_('ITEM DELETED');;

		$cache = &JFactory::getCache('com_projectlog');
		$cache->clean();

		$this->setRedirect( 'index.php?option=com_projectlog&view=categories', $msg );
	}

	function edit( )
	{	
		JRequest::setVar( 'view', 'category' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('category');
		$user	=& JFactory::getUser();

		parent::display();
	}
    
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$task		= JRequest::getVar('task');
		$post = JRequest::get( 'post', JREQUEST_ALLOWRAW);
		$model = $this->getModel('category');
        
		if ($returnid = $model->store($post)) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_projectlog&controller=categories&view=category&hidemainmenu=1&cid[]='.$returnid;
					break;

				default :
					$link = 'index.php?option=com_projectlog&view=categories';
					break;
			}
			$msg	= JText::_( 'ITEM SAVED');
            $type   = 'message';

			$cache = &JFactory::getCache('com_projectlog');
			$cache->clean();

		} else {

			$msg 	= JText::_( 'ITEM NOT SAVED');
			$link = 'index.php?option=com_projectlog&view=categories';
            $type = 'notice';

		}
		$this->setRedirect( $link, $msg, $type );
 	}	
}
?>

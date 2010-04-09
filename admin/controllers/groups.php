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

class projectlogControllergroups extends projectlogController {

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
		$this->setRedirect( 'index.php?option=com_projectlog&view=groups' );
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
        $cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'SELECT ITEM TO DELETE' ) );
		}

		$model = $this->getModel('groups');
        $model->delete($cid);

		$total = count( $cid );
        $msg = $total . ' '.JText::_('ITEM DELETED');;

		$cache = &JFactory::getCache('com_projectlog');
		$cache->clean();

		$this->setRedirect( 'index.php?option=com_projectlog&view=groups', $msg );
	}

	function edit( )
	{
        JRequest::setVar( 'view', 'group' );
		JRequest::setVar( 'hidemainmenu', 1 );
		parent::display();
	}
    
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$task		= JRequest::getVar('task');
		$post = JRequest::get( 'post', JREQUEST_ALLOWRAW);
		$model = $this->getModel('group');

        //echo '<pre>'.var_dump($post).'</pre>';
        
		if ($returnid = $model->store($post)) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_projectlog&controller=groups&view=group&hidemainmenu=1&cid[]='.$returnid;
					break;

				default :
					$link = 'index.php?option=com_projectlog&view=groups';
					break;
			}
			$msg	= JText::_( 'ITEM SAVED');
            $type   = 'message';

			$cache = &JFactory::getCache('com_projectlog');
			$cache->clean();

		} else {

			$msg 	= JText::_( 'ITEM NOT SAVED');
			$link = 'index.php?option=com_projectlog&view=groups';
            $type = 'notice';

		}
		$this->setRedirect( $link, $msg, $type );
 	}
}
?>

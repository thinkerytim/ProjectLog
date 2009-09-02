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

jimport('joomla.application.component.controller');

class ProjectlogController extends JController
{
	function __construct()
	{
		parent::__construct();
	}
	
	function display()
	{
        // Make sure we have a default view
        if( !JRequest::getVar( 'view' )) {
		    JRequest::setVar('view', 'projectlog' );
        }

        $user = JFactory::getUser();
        $useraccess = $user->gid;
        $params     = &JComponentHelper::getParams( 'com_projectlog' );
        $basic_access = $params->get('basic_access');
        $edit_access = $params->get('edit_access');
		
		if($useraccess < $basic_access):
			// Redirect to login
			$uri		= JFactory::getURI();
			$return		= $uri->toString();

			$url  = 'index.php?option=com_user&view=login';
			$url .= '&return='.base64_encode($return);
            
			$this->setRedirect($url, JText::_('You are not authorized to see this area. If you are not logged in, please log in.') );
		else:
        	parent::display();
		endif;

        $task = JRequest::getVar('task');
        switch( $task )
        {
            case 'saveProject':
                if( $useraccess >= $edit_access ):
                    $post = JRequest::get('post');
                    JRequest::checkToken() or die( 'Invalid Token!' );
                    $model = $this->getModel('projectlog');
                    $msg = $model->saveProject($post);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$post['view'].'&Itemid='.$post['Itemid'], false);
                    $this->setRedirect($link, $msg);
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&Itemid='.$post['Itemid'], false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;
            
            case 'deleteProject':
                if( $useraccess >= $edit_access ):
                    $id = JRequest::getVar('id');
                    $item_id = JRequest::getVar('Itemid');
                    $view = JRequest::getVar('view');
                    $model = $this->getModel('projectlog');
                    $msg = $model->deleteProject($id);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&Itemid='.$item_id, false);
                    $this->setRedirect($link, $msg);
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;

            case 'saveLog':
                if( $useraccess >= $edit_access ):
                    $post = JRequest::get('post');
                    JRequest::checkToken() or die( 'Invalid Token!' );
                    $model = $this->getModel('project');
                    $msg = $model->saveLog($post);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$post['view'].'&project_id='.$post['project_id'].'&Itemid='.$post['Itemid'], false);
                    $this->setRedirect($link, $msg);
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&project_id='.JRequest::getVar('project_id').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;

            case 'deleteLog':
                if( $useraccess >= $edit_access ):
                    $id = JRequest::getVar('id');
                    $project_id = JRequest::getVar('project_id');
                    $item_id = JRequest::getVar('Itemid');
                    $view = JRequest::getVar('view');
                    $model = $this->getModel('project');
                    $msg = $model->deleteLog($id);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&project_id='.$project_id.'&Itemid='.$item_id, false);
                    $this->setRedirect($link, $msg);
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&project_id='.JRequest::getVar('project_id').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;

            case 'saveDoc':
                if( $useraccess >= $edit_access ):
                    $post = JRequest::get('post');
                    JRequest::checkToken() or die( 'Invalid Token!' );
                    $model = $this->getModel('project');
                    $msg = $model->saveDoc($post);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$post['view'].'&project_id='.$post['project_id'].'&Itemid='.$post['Itemid'], false);
                    $this->setRedirect($link, $msg);
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&project_id='.JRequest::getVar('project_id').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;
			
			case 'deleteDoc':
                if( $useraccess >= $edit_access ):
                    $id = JRequest::getVar('id');
                    $project_id = JRequest::getVar('project_id');
                    $item_id = JRequest::getVar('Itemid');
                    $view = JRequest::getVar('view');
                    $model = $this->getModel('project');
                    $msg = $model->deleteDoc($id);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&project_id='.$project_id.'&Itemid='.$item_id, false);
                    $this->setRedirect($link, $msg);
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&project_id='.JRequest::getVar('project_id').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;

            case "projectOnsite":
                if( $useraccess >= $edit_access ):
                    $cid = JRequest::getVar('project_edit');
                    $item_id = JRequest::getVar('Itemid');
                    $view = JRequest::getVar('view');
                    $model = $this->getModel('projectlog');
                    $model->projectSitestatus($cid, 1);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&project_id='.$project_id.'&Itemid='.$item_id, false);
                    $this->setRedirect($link, '');
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;

            case "projectOffsite":
                if( $useraccess >= $edit_access ):
                    $cid = JRequest::getVar('project_edit');
                    $item_id = JRequest::getVar('Itemid');
                    $view = JRequest::getVar('view');
                    $model = $this->getModel('projectlog');
                    $model->projectSitestatus($cid, 0);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&project_id='.$project_id.'&Itemid='.$item_id, false);
                    $this->setRedirect($link, '');
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;

            case "changeStatus":
                if( $useraccess >= $edit_access ):
                    $cid = JRequest::getVar('project_edit');
                    $item_id = JRequest::getVar('Itemid');
                    $view = JRequest::getVar('view');
                    $model = $this->getModel('projectlog');
                    $model->changeStatus($cid);
                    $link = JRoute::_('index.php?option=com_projectlog&view='.$view.'&project_id='.$project_id.'&Itemid='.$item_id, false);
                    $this->setRedirect($link, '');
                else:
                    $link = JRoute::_('index.php?option=com_projectlog&view='.JRequest::getVar('view').'&Itemid='.JRequest::getVar('Itemid'), false);
                    $this->setRedirect($link, 'You are not authorized to perform this action.');
                endif;
            break;
        }
	}		
}
